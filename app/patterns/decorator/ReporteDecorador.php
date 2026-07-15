<?php

/**
 * Patrón Decorator
 * Añade responsabilidades a los reportes de forma dinámica.
 * Diagrama: Reporte → ReporteBase | ReporteConFiltro | ReportePDF | ReporteExcel
 *
 * Corrección: ReportePDF ahora genera una tabla HTML real con los datos del
 * inventario. ReporteExcel genera CSV correcto con cabeceras.
 * La interfaz Reporte devuelve string para máxima compatibilidad con la vista.
 */

// ─── Interfaz base ────────────────────────────────────────────────────────────

interface Reporte {
    public function generarReporte(): string;
}

// ─── Componente concreto ──────────────────────────────────────────────────────

class ReporteBase implements Reporte {
    private array $datos;

    public function __construct(array $datos) {
        $this->datos = $datos;
    }

    /**
     * Genera el reporte en texto plano (campo: valor | campo: valor).
     * Sirve como base para que los decoradores lo envuelvan.
     */
    public function generarReporte(): string {
        if (empty($this->datos)) {
            return 'Sin datos para mostrar.';
        }
        $lineas = [];
        foreach ($this->datos as $fila) {
            $item     = is_object($fila) ? (array)$fila : $fila;
            $lineas[] = implode(' | ', array_map(
                fn($k, $v) => "$k: $v",
                array_keys($item),
                array_values($item)
            ));
        }
        return implode("\n", $lineas);
    }

    /** Expone los datos originales para que los decoradores puedan trabajar con ellos. */
    public function getDatos(): array {
        return $this->datos;
    }
}

// ─── Decorador abstracto ──────────────────────────────────────────────────────

abstract class ReporteDecorador implements Reporte {
    protected Reporte $reporte;

    public function __construct(Reporte $reporte) {
        $this->reporte = $reporte;
    }

    public function generarReporte(): string {
        return $this->reporte->generarReporte();
    }
}

// ─── Decoradores concretos ────────────────────────────────────────────────────

/**
 * Aplica un filtro por campo=valor sobre los datos del reporte base.
 * Uso: new ReporteConFiltro($base, 'tipo', 'Tela')
 */
class ReporteConFiltro extends ReporteDecorador {
    private string $campo;
    private string $valor;

    public function __construct(Reporte $reporte, string $campo, string $valor) {
        parent::__construct($reporte);
        $this->campo = $campo;
        $this->valor = $valor;
    }

    public function generarReporte(): string {
        $datos = $this->reporte instanceof ReporteBase
            ? $this->reporte->getDatos()
            : [];

        $filtrados = array_values(array_filter($datos, function ($fila) {
            $item = is_object($fila) ? $fila : (object)$fila;
            return isset($item->{$this->campo}) &&
                   stripos((string)$item->{$this->campo}, $this->valor) !== false;
        }));

        return (new ReporteBase($filtrados))->generarReporte();
    }

    public function getDatosFiltrados(): array {
        $datos = $this->reporte instanceof ReporteBase
            ? $this->reporte->getDatos()
            : [];

        return array_values(array_filter($datos, function ($fila) {
            $item = is_object($fila) ? $fila : (object)$fila;
            return isset($item->{$this->campo}) &&
                   stripos((string)$item->{$this->campo}, $this->valor) !== false;
        }));
    }
}

/**
 * Genera una tabla HTML completa lista para imprimir o convertir a PDF.
 * Construye la tabla directamente desde los objetos de BD,
 * NO convierte texto plano (eso sería perder información).
 */
class ReportePDF extends ReporteDecorador {

    public function generarReporte(): string {
        $datos = $this->reporte instanceof ReporteBase
            ? $this->reporte->getDatos()
            : [];

        return $this->construirTablaHTML($datos);
    }

    private function construirTablaHTML(array $datos): string {
        $fecha = date('d/m/Y H:i');
        $html  = "<!DOCTYPE html>
<html lang='es'>
<head>
<meta charset='UTF-8'>
<title>Reporte Inventario - Creaciones Justean</title>
<style>
  body  { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; color: #222; }
  h2    { color: #1a3c5e; margin-bottom: 4px; }
  small { color: #666; }
  table { width: 100%; border-collapse: collapse; margin-top: 16px; }
  th    { background: #1a3c5e; color: #fff; padding: 8px 10px; text-align: left; }
  td    { padding: 7px 10px; border-bottom: 1px solid #ddd; }
  tr:nth-child(even) td { background: #f5f8fc; }
  .alerta td { background: #fff0f0 !important; color: #b00; }
  .text-end { text-align: right; }
  tfoot td   { background: #1a3c5e; color: #fff; font-weight: bold; padding: 8px 10px; }
</style>
</head>
<body>
<h2>Reporte de Inventario — Creaciones Justean</h2>
<small>Generado: {$fecha}</small>";

        if (empty($datos)) {
            return $html . '<p>Sin datos para mostrar.</p></body></html>';
        }

        $html .= "
<table>
  <thead>
    <tr>
      <th>Insumo</th><th>Tipo</th><th>Unidad</th>
      <th class='text-end'>Stock Actual</th>
      <th class='text-end'>Stock Mínimo</th>
      <th class='text-end'>Costo Unit.</th>
      <th class='text-end'>Valor Total</th>
    </tr>
  </thead>
  <tbody>";

        $totalInventario = 0;
        foreach ($datos as $fila) {
            $i              = is_object($fila) ? $fila : (object)$fila;
            $valorTotal     = (float)$i->stockActual * (float)$i->costo;
            $totalInventario += $valorTotal;
            $alertaClass    = ((float)$i->stockActual < (float)$i->stockMinimo && (float)$i->stockMinimo > 0)
                              ? ' class="alerta"' : '';
            $html .= "
    <tr{$alertaClass}>
      <td>" . htmlspecialchars($i->nombre)      . "</td>
      <td>" . htmlspecialchars($i->tipo)        . "</td>
      <td>" . htmlspecialchars($i->unidadMedida). "</td>
      <td class='text-end'>" . number_format((float)$i->stockActual, 2) . "</td>
      <td class='text-end'>" . number_format((float)$i->stockMinimo, 2) . "</td>
      <td class='text-end'>$" . number_format((float)$i->costo, 2)      . "</td>
      <td class='text-end'>$" . number_format($valorTotal, 2)           . "</td>
    </tr>";
        }

        $html .= "
  </tbody>
  <tfoot>
    <tr>
      <td colspan='6' class='text-end'>Valor Total del Inventario</td>
      <td class='text-end'>\$" . number_format($totalInventario, 2) . "</td>
    </tr>
  </tfoot>
</table>
</body></html>";

        return $html;
    }
}

/**
 * Genera CSV real con cabeceras, listo para abrir en Excel.
 * Construye desde los objetos de BD directamente.
 */
class ReporteExcel extends ReporteDecorador {

    public function generarReporte(): string {
        $datos = $this->reporte instanceof ReporteBase
            ? $this->reporte->getDatos()
            : [];

        return $this->construirCSV($datos);
    }

    private function construirCSV(array $datos): string {
        if (empty($datos)) {
            return 'Sin datos';
        }

        $cabecera = [
            'ID', 'Nombre', 'Descripcion', 'Tipo', 'Unidad de Medida',
            'Stock Actual', 'Stock Minimo', 'Costo Unitario', 'Valor Total Stock'
        ];

        $filas   = [];
        $filas[] = $this->filaCSV($cabecera);

        foreach ($datos as $fila) {
            $i       = is_object($fila) ? $fila : (object)$fila;
            $filas[] = $this->filaCSV([
                $i->idInsumo    ?? '',
                $i->nombre      ?? '',
                $i->descripcion ?? '',
                $i->tipo        ?? '',
                $i->unidadMedida?? '',
                number_format((float)($i->stockActual ?? 0), 2),
                number_format((float)($i->stockMinimo ?? 0), 2),
                number_format((float)($i->costo       ?? 0), 2),
                number_format((float)($i->stockActual ?? 0) * (float)($i->costo ?? 0), 2),
            ]);
        }

        return implode("\n", $filas);
    }

    private function filaCSV(array $campos): string {
        return implode(',', array_map(
            fn($c) => '"' . str_replace('"', '""', (string)$c) . '"',
            $campos
        ));
    }
}
