<?php

class OperarioController extends Controller {
private $insumoModel;
    private $movimientoInventarioModel;
    private $proveedorModel;
    private $pedidoModel;
     public function __construct() {
        // --- Verificación de Seguridad ---
        if (!isset($_SESSION['idUsuario'])) {
            header('Location: ' . URLROOT . 'auth/index');
            exit();
        }

        // Cargar los modelos necesarios para las operaciones
        $this->insumoModel = $this->model('Insumo');
        $this->movimientoInventarioModel = $this->model('MovimientoInventario');
        $this->proveedorModel = $this->model('Proveedor');
        $this->pedidoModel = $this->model('Pedido');
    }

    /**
     * Método principal que carga el dashboard del operario.
     */
    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $data = [
            'title' => 'Dashboard Operario'
        ];
        
        $this->view('operario/dashboard', $data);
    }
    
    /**
     * Carga la vista para registrar una nueva entrada de insumos.
     * Corresponde al Caso de Uso 2.
     */
    public function registrarEntrada() {
        // Si la solicitud es POST, procesar el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = (object)[
                'idInsumo'       => trim($_POST['idInsumo']),
                'cantidad'       => (float)trim($_POST['cantidad']),
                'idProveedor'    => trim($_POST['idProveedor']),
                'idUsuario'      => $_SESSION['idUsuario'],
                'tipoMovimiento' => 'entrada',
                'idPedido'       => null,
                'idOrdenCompra'  => null,
            ];

            // Patrón Command: encapsula la operación de entrada como comando ejecutable
            $comando = new RegistrarEntradaComando(
                $datos,
                $this->insumoModel,
                $this->movimientoInventarioModel
            );
            $invocador = new Invocador();
            $invocador->setComando($comando);
            $ok = $invocador->ejecutarComando();

            // Patrón Observer: si la entrada fue exitosa, verificar alertas de stock
            if ($ok) {
                $insumoActualizado = $this->insumoModel->obtenerPorId($datos->idInsumo);
                if ($insumoActualizado) {
                    $gestor = GestorEventosFactory::crear();
                    $gestor->verificarAlertaStock($insumoActualizado);
                }
            }

            header('Location: ' . URLROOT . 'operario/registrarEntrada');
            exit();
        }

        // Si es GET, preparar los datos para el formulario
        $insumos = $this->insumoModel->obtenerTodos();
        $proveedores = $this->proveedorModel->obtenerTodos();
        
        $data = [
            'title' => 'Registrar Entrada de Insumos',
            'insumos' => $insumos,
            'proveedores' => $proveedores
        ];
        $this->view('operario/registrar_entrada', $data);
    }

    /**
     * Carga la vista para registrar una salida de insumos para un pedido.
     * Corresponde al Caso de Uso 3.
     */
    public function registrarSalida() {
        // Si la solicitud es POST, procesar el formulario
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = (object)[
                'idInsumo'       => trim($_POST['idInsumo']),
                'cantidad'       => (float)trim($_POST['cantidad']),
                'idPedido'       => trim($_POST['idPedido']),
                'idUsuario'      => $_SESSION['idUsuario'],
                'tipoMovimiento' => 'salida',
                'idOrdenCompra'  => null,
            ];

            // Patrón Strategy: calcular stock disponible real antes de descontar
            $calculador = CalculadorStock::conEstrategia('fifo');
            $insumo     = $this->insumoModel->obtenerPorId($datos->idInsumo);
            // stockActual = disponible físico; 0 = sin reservas registradas aparte
            $stockReal  = $insumo
                ? $calculador->obtenerStock((float)$insumo->stockActual, 0)
                : 0;

            if ($stockReal >= $datos->cantidad) {
                // Patrón Command: encapsula la salida como comando deshacible
                // La verificación ya fue hecha por Strategy arriba, el comando solo ejecuta
                $comando = new RegistrarSalidaComando(
                    $datos,
                    $this->insumoModel,
                    $this->movimientoInventarioModel
                );
                $invocador = new Invocador();
                $invocador->setComando($comando);
                $ok = $invocador->ejecutarComando();

                // Patrón Observer: notificar si el stock quedó bajo mínimo tras la salida
                if ($ok) {
                    $insumoActualizado = $this->insumoModel->obtenerPorId($datos->idInsumo);
                    if ($insumoActualizado) {
                        $gestor = GestorEventosFactory::crear();
                        $gestor->verificarAlertaStock($insumoActualizado);
                    }
                }
            }

            header('Location: ' . URLROOT . 'operario/registrarSalida');
            exit();
        }
        
        // Si es GET, preparar los datos para el formulario
        $insumos = $this->insumoModel->obtenerTodos();
        $pedidos = $this->pedidoModel->obtenerTodos(); // Usaremos un método del nuevo modelo
        
        $data = [
            'title' => 'Registrar Salida de Insumos',
            'insumos' => $insumos,
            'pedidos' => $pedidos
        ];
        $this->view('operario/registrar_salida', $data);
    }

    /**
     * Carga la vista para vincular insumos a un pedido existente.
     * Corresponde al Caso de Uso 5.
     */
    public function vincularInsumoPedido() {
        // Si la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idPedido = trim($_POST['idPedido']);
            $idInsumo = trim($_POST['idInsumo']);
            $cantidad = trim($_POST['cantidad']);

            // Llama al método del modelo Pedido para crear la asociación
            $this->pedidoModel->asignarInsumo($idPedido, $idInsumo, $cantidad);
            
            header('Location: ' . URLROOT . 'operario/vincularInsumoPedido');
            exit();
        }
        
        // Si es GET, preparar los datos para el formulario
        $insumos = $this->insumoModel->obtenerTodos();
        $pedidos = $this->pedidoModel->obtenerTodos();
        
        $data = [
            'title' => 'Vincular Insumos a Pedido',
            'insumos' => $insumos,
            'pedidos' => $pedidos
        ];
        $this->view('operario/vincular_insumo_pedido', $data);
    }
}