<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="mb-0"><?php echo $data['title']; ?></h3>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>administrador/generarReporte" method="POST">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label for="tipo_reporte" class="form-label fw-semibold">Tipo de reporte</label>
                    <select name="tipo_reporte" id="tipo_reporte" class="form-select">
                        <option value="inventario_actual">Inventario Actual</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="formato" class="form-label fw-semibold">Formato (Decorator)</label>
                    <select name="formato" id="formato" class="form-select">
                        <option value="tabla">Tabla en pantalla</option>
                        <option value="pdf">Vista PDF (ReportePDF)</option>
                        <option value="excel">Exportar CSV (ReporteExcel)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-chart-bar me-2"></i>Generar Reporte
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($data['reporte_data'])) : ?>

    <?php /* Patrón Iterator: alertas de bajo stock detectadas al recorrer el inventario */ ?>
    <?php if (!empty($data['alertas_stock'])): ?>
    <div class="alert alert-danger mb-3">
        <strong>
            <i class="fa-solid fa-exclamation-triangle me-2"></i>
            <?php echo count($data['alertas_stock']); ?> insumo(s) con stock bajo mínimo:
        </strong>
        <ul class="mb-0 mt-1">
            <?php foreach($data['alertas_stock'] as $ins): ?>
                <li>
                    <?php echo htmlspecialchars($ins->nombre); ?> —
                    Actual: <strong><?php echo $ins->stockActual; ?></strong> /
                    Mínimo: <?php echo $ins->stockMinimo; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php /* Patrón Decorator: mostrar salida según el decorador elegido */ ?>
    <?php if ($data['formato'] === 'pdf'): ?>
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-layer-group me-2 text-primary"></i>
                    <strong>Decorator aplicado:</strong> ReportePDF — Tabla HTML lista para imprimir
                </span>
                <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
                    <i class="fa-solid fa-print me-1"></i>Imprimir / Guardar PDF
                </button>
            </div>
            <div class="card-body p-0">
                <?php /* Inyecta directamente el HTML generado por ReportePDF */ ?>
                <?php echo $data['reporte_html']; ?>
            </div>
        </div>

    <?php elseif ($data['formato'] === 'excel'): ?>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa-solid fa-layer-group me-2 text-success"></i>
                <strong>Decorator aplicado:</strong> ReporteExcel — Formato CSV
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">
                    Contenido CSV generado. Copia y pega en Excel, o descarga el archivo.
                </p>
                <textarea class="form-control font-monospace" rows="10" readonly
                    style="font-size:12px;"><?php echo htmlspecialchars($data['reporte_html']); ?></textarea>
                <a href="data:text/csv;charset=utf-8,<?php echo rawurlencode($data['reporte_html']); ?>"
                   download="inventario_justean_<?php echo date('Ymd'); ?>.csv"
                   class="btn btn-success mt-2">
                    <i class="fa-solid fa-file-csv me-1"></i>Descargar CSV
                </a>
            </div>
        </div>

    <?php else: ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Inventario Actual</h4>
                <small class="text-muted">
                    <i class="fa-solid fa-layer-group me-1"></i>
                    Decorator: ReporteBase (tabla directa)
                </small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Insumo</th>
                                <th>Tipo</th>
                                <th class="text-center">Stock Actual</th>
                                <th class="text-center">Stock Mínimo</th>
                                <th class="text-end">Costo Unitario</th>
                                <th class="text-end">Valor Total del Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $valor_total_inventario = 0;
                                foreach($data['reporte_data'] as $insumo):
                                    $valor_stock_insumo      = $insumo->stockActual * $insumo->costo;
                                    $valor_total_inventario += $valor_stock_insumo;
                                    $bajo = ($insumo->stockActual < $insumo->stockMinimo && $insumo->stockMinimo > 0);
                            ?>
                                <tr <?php echo $bajo ? 'class="table-danger"' : ''; ?>>
                                    <td><?php echo htmlspecialchars($insumo->nombre); ?></td>
                                    <td><?php echo htmlspecialchars($insumo->tipo); ?></td>
                                    <td class="text-center">
                                        <?php echo $insumo->stockActual; ?>
                                        <?php if($bajo): ?>
                                            <i class="fa-solid fa-exclamation-triangle text-danger ms-1" title="Stock bajo mínimo"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?php echo $insumo->stockMinimo; ?></td>
                                    <td class="text-end">$<?php echo number_format($insumo->costo, 2); ?></td>
                                    <td class="text-end">$<?php echo number_format($valor_stock_insumo, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-dark">
                                <td colspan="5" class="text-end fw-bold">Valor Total del Inventario</td>
                                <td class="text-end fw-bold">$<?php echo number_format($valor_total_inventario, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
