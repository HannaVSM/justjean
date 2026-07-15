<?php

class AdministradorController extends Controller {

    private $userModel;
    private $proveedorModel;
    private $insumoModel; // Añadimos la propiedad para el modelo Insumo
    private $ordenDeCompraModel;      // Añadimos propiedad
    private $detalleOrdenCompraModel; 
    private $pedidoModel;
    private $movimientoModel;

    public function __construct() {
        // Cargar modelos
        $this->userModel = $this->model('Usuario');
        $this->proveedorModel = $this->model('Proveedor');
        $this->insumoModel = $this->model('Insumo');
        $this->ordenDeCompraModel = $this->model('OrdenDeCompra');
        $this->detalleOrdenCompraModel = $this->model('DetalleOrdenCompra');
        $this->pedidoModel = $this->model('Pedido');
        $this->movimientoModel = $this->model('MovimientoInventario');

        // --- Verificación de Seguridad ---
        if (!isset($_SESSION['idUsuario'])) {
            header('Location: ' . URLROOT . 'auth/index');
            exit();
        }

        if (!$this->userModel->verificarPermiso('Administrador')) {
            header('Location: ' . URLROOT . 'operario/dashboard');
            exit();
        }
    }

    public function index() {
        $this->dashboard();
    }
    
    public function dashboard() {
        $pedidoModel = $this->model('Pedido');
        $movimientosRecientes = $this->movimientoModel->getRecientes(5);
        $totalInsumos    = $this->insumoModel->contarTodos();
        $totalProveedores= $this->proveedorModel->contarTodos();
        $pedidosActivos  = $pedidoModel->contarActivos();

        // Patrón Iterator: recorrer todos los insumos para detectar alertas de stock
        $todosInsumos = $this->insumoModel->obtenerTodos();
        $agregado     = new InventarioAgregado(is_array($todosInsumos) ? $todosInsumos : []);
        $iterBajoStock = $agregado->iteradorBajoStock();
        $alertasDetalle = [];
        while ($iterBajoStock->hasMore()) {
            $alertasDetalle[] = $iterBajoStock->getNext();
        }
        $alertasStock = count($alertasDetalle);

        $data = [
            'title'           => 'Dashboard Administrador',
            'totalInsumos'    => $totalInsumos,
            'alertasStock'    => $alertasStock,
            'alertasDetalle'  => $alertasDetalle,   // Para mostrar lista en dashboard
            'totalProveedores'=> $totalProveedores,
            'pedidosActivos'  => $pedidosActivos,
            // Patrón Observer: notificaciones almacenadas en sesión por NotificadorUI
            'notificaciones'  => $_SESSION['notificaciones'] ?? [],
            'movimientos' => $movimientosRecientes
        ];

        // Limpiar notificaciones una vez mostradas
        unset($_SESSION['notificaciones']);

        $this->view('administrador/dashboard', $data);
    }

    public function gestionarProveedores() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'idProveedor' => isset($_POST['idProveedor']) ? trim($_POST['idProveedor']) : '',
                'nombre' => isset($_POST['nombre']) ? trim($_POST['nombre']) : '',
                'contacto' => isset($_POST['contacto']) ? trim($_POST['contacto']) : '',
                'email' => isset($_POST['email']) ? trim($_POST['email']) : ''
            ];

            switch ($_POST['action']) {
                case 'crear':
                    $this->proveedorModel->crear($data);
                    break;
                case 'editar':
                    $this->proveedorModel->actualizar($data);
                    break;
                case 'eliminar':
                    $this->proveedorModel->eliminar($data['idProveedor']);
                    break;
            }
            header('Location: ' . URLROOT . 'administrador/gestionarProveedores');
            exit();
        }

        $proveedores = $this->proveedorModel->obtenerTodos();
        $data = [
            'title' => 'Gestionar Proveedores',
            'proveedores' => $proveedores
        ];
        
        $this->view('administrador/gestionar_proveedores', $data);
    }


    /**
     * Gestiona el CRUD para Insumos.
     * Corresponde al Caso de Uso 1: Registrar nuevo insumo.
     */
    public function registrarInsumo() {
        // Si la solicitud es POST, procesar la acción
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'idInsumo'    => isset($_POST['idInsumo'])    ? trim($_POST['idInsumo'])    : '',
                'nombre'      => isset($_POST['nombre'])      ? trim($_POST['nombre'])      : '',
                'descripcion' => isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '',
                'tipo'        => isset($_POST['tipo'])        ? trim($_POST['tipo'])        : '',
                'unidadMedida'=> isset($_POST['unidadMedida'])? trim($_POST['unidadMedida']): '',
                'stockActual' => isset($_POST['stockActual']) ? trim($_POST['stockActual']) : 0,
                'stockMinimo' => isset($_POST['stockMinimo']) ? trim($_POST['stockMinimo']) : 0,
                'costo'       => isset($_POST['costo'])       ? trim($_POST['costo'])       : 0.0,
            ];

            switch ($_POST['action']) {
                case 'crear':
                    // Patrón Abstract Factory: crea el insumo usando la fábrica del tipo elegido
                    $fabrica        = InsumoFactorySelector::obtener($data['tipo']);
                    $insumoProducto = $fabrica->crearInsumo($data);
                    // Sincroniza el tipo normalizado con el dato persistido en BD
                    $data['tipo']   = $insumoProducto->obtenerTipo();
                    $this->insumoModel->crear($data);
                    break;
                case 'editar':
                    $this->insumoModel->actualizar($data);
                    break;
                case 'eliminar':
                    $this->insumoModel->eliminar($data['idInsumo']);
                    break;
            }
            header('Location: ' . URLROOT . 'administrador/registrarInsumo');
            exit();
        }

        // Si la solicitud es GET, mostrar la lista de insumos
        $insumos = $this->insumoModel->obtenerTodos();
        $data = [
            'title' => 'Gestionar Insumos',
            'insumos' => $insumos
        ];

        $this->view('administrador/registrar_insumo', $data);
    }
    public function definirStockMinimo() {
        // Si la solicitud es POST, procesar la actualización
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['stock_minimo']) && is_array($_POST['stock_minimo'])) {
                foreach ($_POST['stock_minimo'] as $idInsumo => $minimo) {
                    $this->insumoModel->actualizarStockMinimo((int)$idInsumo, (int)$minimo);
                }
            }
            header('Location: ' . URLROOT . 'administrador/definirStockMinimo');
            exit();
        }

        // Si es GET, mostrar la lista de insumos
        $insumos = $this->insumoModel->obtenerTodos();
        $data = [
            'title' => 'Definir Nivel de Stock Mínimo',
            'insumos' => $insumos
        ];
        $this->view('administrador/definir_stock_minimo', $data);
    }
    public function generarReporte() {
        $data = [
            'title'        => 'Generar Reportes',
            'reporte_data' => null,
            'reporte_tipo' => '',
            'reporte_html' => '',
            'alertas_stock'=> [],
            'formato'      => 'tabla',
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tipoReporte = $_POST['tipo_reporte'];
            $formato     = $_POST['formato'] ?? 'tabla';
            $data['formato'] = $formato;

            switch ($tipoReporte) {
                case 'inventario_actual':
                    $insumos = $this->insumoModel->obtenerTodos();
                    $data['reporte_data'] = $insumos;
                    $data['reporte_tipo'] = 'inventario_actual';

                    // Patrón Decorator: aplica el decorador según el formato elegido por el usuario
                    $base = new ReporteBase(is_array($insumos) ? $insumos : []);
                    if ($formato === 'pdf') {
                        // ReportePDF: genera tabla HTML completa lista para imprimir
                        $data['reporte_html'] = (new ReportePDF($base))->generarReporte();
                    } elseif ($formato === 'excel') {
                        // ReporteExcel: genera CSV con cabeceras listo para descargar
                        $data['reporte_html'] = (new ReporteExcel($base))->generarReporte();
                    } else {
                        // Sin decorador adicional: la vista renderiza la tabla directamente
                        $data['reporte_html'] = '';
                    }

                    // Patrón Iterator: detectar insumos bajo stock mínimo
                    $agregado = new InventarioAgregado(is_array($insumos) ? $insumos : []);
                    $iterador = $agregado->iteradorBajoStock();
                    $alertas  = [];
                    while ($iterador->hasMore()) {
                        $alertas[] = $iterador->getNext();
                    }
                    $data['alertas_stock'] = $alertas;
                    break;
            }
        }

        $this->view('administrador/generar_reporte', $data);
    }
    public function gestionarOrdenesCompra() {
        $ordenes = $this->ordenDeCompraModel->obtenerTodas();
        $data = [
            'title' => 'Gestionar Órdenes de Compra',
            'ordenes' => $ordenes
        ];
        $this->view('administrador/gestionar_ordenes_compra', $data);
    }
     public function crearOrdenCompra() {
        // Si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Crear la cabecera de la orden
            $ordenData = [
                'idProveedor' => $_POST['idProveedor'],
                'fecha' => $_POST['fecha'],
                'estado' => 'Pendiente'
            ];
            $idOrdenCompra = $this->ordenDeCompraModel->crear($ordenData);

            // 2. Si la orden se creó, agregar los detalles
            if ($idOrdenCompra) {
                for ($i = 0; $i < count($_POST['insumos']); $i++) {
                    $detalleData = [
                        'idOrdenCompra' => $idOrdenCompra,
                        'idInsumo' => $_POST['insumos'][$i],
                        'cantidadSolicitada' => $_POST['cantidades'][$i],
                        'costoUnitario' => $_POST['costos'][$i]
                    ];
                    $this->detalleOrdenCompraModel->agregarInsumo($detalleData);
                }
            }
            header('Location: ' . URLROOT . 'administrador/gestionarOrdenesCompra');
            exit();
        }

        // Si es GET, preparar datos para el formulario
        $proveedores = $this->proveedorModel->obtenerTodos();
        $insumos = $this->insumoModel->obtenerTodos();
        $data = [
            'title' => 'Crear Nueva Orden de Compra',
            'proveedores' => $proveedores,
            'insumos' => $insumos
        ];
        $this->view('administrador/crear_orden_compra', $data);
    }

    /**
     * Muestra el detalle de una orden de compra específica.
     * @param int $id El ID de la orden de compra.
     */
    public function verDetalleOrden($id) {
        $orden = $this->ordenDeCompraModel->obtenerPorId($id);
        $detalles = $this->detalleOrdenCompraModel->obtenerPorOrdenId($id);

        $data = [
            'title' => 'Detalle de Orden de Compra #' . $id,
            'orden' => $orden,
            'detalles' => $detalles
        ];
        $this->view('administrador/ver_detalle_orden', $data);
    }

    public function gestionarPedidos() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $accion = $_POST['action'] ?? 'crear';

            if ($accion === 'cambiar_estado') {
                // Patrón State: transición de estado del pedido
                $idPedido     = (int)$_POST['idPedido'];
                $transicion   = $_POST['transicion'] ?? '';
                $pedidoDB     = $this->pedidoModel->obtenerPorId($idPedido);

                if ($pedidoDB) {
                    // Callback que persiste el nuevo estado en BD
                    $persistir = function(int $id, string $nuevoEstado) {
                        $this->pedidoModel->actualizarEstado($id, $nuevoEstado);
                    };
                    $contexto = new PedidoContexto($idPedido, $pedidoDB->estado, $persistir);

                    switch ($transicion) {
                        case 'aprobar':   $contexto->aprobar();   break;
                        case 'iniciar':   $contexto->iniciar();   break;
                        case 'finalizar': $contexto->finalizar(); break;
                        case 'cancelar':  $contexto->cancelar();  break;
                    }
                }

            } elseif ($accion === 'duplicar') {
                // Patrón Prototype: clonar un pedido existente
                $idPedido  = (int)$_POST['idPedido'];
                $pedidoDB  = $this->pedidoModel->obtenerPorId($idPedido);

                if ($pedidoDB) {
                    $original    = PedidoConcreto::desdeDB($pedidoDB);
                    $duplicador  = new DuplicarPedido();
                    $clon        = $duplicador->duplicarPedido($original);
                    $this->pedidoModel->crear($clon->toArray());
                }

            } else {
                // Crear nuevo pedido (comportamiento original)
                $data = [
                    'cliente' => trim($_POST['cliente']),
                    'fecha'   => trim($_POST['fecha']),
                    'estado'  => 'Registrado',
                ];
                $this->pedidoModel->crear($data);
            }

            header('Location: ' . URLROOT . 'administrador/gestionarPedidos');
            exit();
        }

        $pedidos = $this->pedidoModel->obtenerTodos();
        $data = [
            'title'   => 'Gestionar Pedidos de Clientes',
            'pedidos' => $pedidos,
        ];
        $this->view('administrador/gestionar_pedidos', $data);
    }
}