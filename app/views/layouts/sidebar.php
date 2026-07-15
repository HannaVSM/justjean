<div id="sidebar-wrapper">

    <!-- LOGO -->
    <div class="sidebar-heading text-light text-center py-4">
        <i class="fa-solid fa-shirt me-2"></i>Creaciones Justean
    </div>

    <?php if(isset($_SESSION['rol'])) : ?>

        <?php if($_SESSION['rol'] == 'Administrador') : ?>

            <!-- PRINCIPAL -->
            <div class="sidebar-section">Principal</div>

            <a href="<?php echo URLROOT; ?>administrador/dashboard" 
               class="sidebar-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : ''; ?>">
               <i class="fa-solid fa-gauge-high me-2"></i>Dashboard
            </a>

            <!-- INVENTARIO -->
            <div class="sidebar-section">Inventario</div>

            <a href="<?php echo URLROOT; ?>operario/registrarEntrada" class="sidebar-item">
                <i class="fa-solid fa-arrow-down me-2"></i>Registrar entrada
            </a>

            <a href="<?php echo URLROOT; ?>operario/registrarSalida" class="sidebar-item">
                <i class="fa-solid fa-arrow-up me-2"></i>Registrar salida
            </a>

            <a href="<?php echo URLROOT; ?>administrador/registrarInsumo" class="sidebar-item">
                <i class="fa-solid fa-box me-2"></i>Insumos
            </a>

            <a href="<?php echo URLROOT; ?>administrador/definirStockMinimo" class="sidebar-item">
                <i class="fa-solid fa-bell me-2"></i>Stock mínimo
            </a>

            <!-- OPERACIONES -->
            <div class="sidebar-section">Operaciones</div>

            <a href="<?php echo URLROOT; ?>administrador/gestionarPedidos" class="sidebar-item">
                <i class="fa-solid fa-clipboard-list me-2"></i>Pedidos
            </a>

            <a href="<?php echo URLROOT; ?>operario/vincularInsumoPedido" class="sidebar-item">
                <i class="fa-solid fa-link me-2"></i>Vincular insumo
            </a>

            <a href="<?php echo URLROOT; ?>administrador/gestionarProveedores" class="sidebar-item">
                <i class="fa-solid fa-truck me-2"></i>Proveedores
            </a>

            <!-- ANÁLISIS -->
            <div class="sidebar-section">Análisis</div>

            <a href="<?php echo URLROOT; ?>administrador/generarReporte" class="sidebar-item">
                <i class="fa-solid fa-chart-column me-2"></i>Reportes
            </a>

        <?php elseif($_SESSION['rol'] == 'Operario') : ?>

            <div class="sidebar-section">Principal</div>

            <a href="<?php echo URLROOT; ?>operario/dashboard" class="sidebar-item active">
                <i class="fa-solid fa-gauge-high me-2"></i>Dashboard
            </a>

            <div class="sidebar-section">Operaciones</div>

            <a href="<?php echo URLROOT; ?>operario/registrarEntrada" class="sidebar-item">
                <i class="fa-solid fa-arrow-down me-2"></i>Registrar entrada
            </a>

            <a href="<?php echo URLROOT; ?>operario/registrarSalida" class="sidebar-item">
                <i class="fa-solid fa-arrow-up me-2"></i>Registrar salida
            </a>

            <a href="<?php echo URLROOT; ?>operario/vincularInsumoPedido" class="sidebar-item">
                <i class="fa-solid fa-link me-2"></i>Vincular insumo
            </a>

        <?php endif; ?>

    <?php endif; ?>

</div>