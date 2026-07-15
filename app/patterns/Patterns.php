<?php

/**
 * Cargador central de Patrones de Diseño
 * Incluir este archivo en cualquier controlador o script para acceder
 * a todos los patrones implementados.
 *
 * Uso:
 *   require_once 'app/patterns/Patterns.php';
 */

$patternBase = __DIR__;

require_once $patternBase . '/factory/InsumoFactory.php';
require_once $patternBase . '/iterator/InventarioIterador.php';
require_once $patternBase . '/decorator/ReporteDecorador.php';
require_once $patternBase . '/command/ComandoInventario.php';
require_once $patternBase . '/observer/ObserverInventario.php';
require_once $patternBase . '/state/EstadoPedido.php';
require_once $patternBase . '/bridge/Repositorio.php';
require_once $patternBase . '/strategy/EstrategiaStock.php';
require_once $patternBase . '/prototype/PedidoPrototype.php';
