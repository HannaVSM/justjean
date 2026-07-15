<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : 'Sistema de Inventarios'; ?> - Creaciones Justean</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Tus estilos -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">

<style>

/* 🔥 FONDO GENERAL */
body {
    background: #f5f7fb;
}

/* 🔥 NAVBAR */
.navbar {
    border-radius: 0 !important;
}

/* 🔥 KPI CARDS */
.kpi-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    overflow: hidden;
    height: 100%;
}

.kpi-bar { height: 4px; }
.kpi-body { padding: 15px; }

.kpi-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
}

.kpi-value {
    font-size: 28px;
    font-weight: bold;
}

.kpi-sub {
    font-size: 0.8rem;
    color: #6b7280;
}

/* 🔥 SIDEBAR */
#sidebar-wrapper {
    background: #0f172a;
    min-height: 100vh;
    width: 250px;
}

.sidebar-heading {
    font-weight: bold;
    font-size: 1.1rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.sidebar-section {
    font-size: 0.7rem;
    color: #64748b;
    padding: 15px 20px 5px;
    text-transform: uppercase;
}

.sidebar-item {
    display: block;
    padding: 10px 20px;
    color: #cbd5f5;
    text-decoration: none;
    border-radius: 8px;
    margin: 4px 10px;
}

.sidebar-item:hover {
    background: rgba(255,255,255,0.05);
    color: #fff;
}

.sidebar-item.active {
    background: #1e293b;
    color: #fff;
}

/* 🔥 UI EXTRA */
.dropdown-menu {
    border-radius: 10px;
    border: none;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.badge {
    font-weight: 500;
}

</style>

</head>

<body>

<div class="d-flex" id="wrapper">
    
    <?php require_once 'sidebar.php'; ?>

    <!-- CONTENIDO -->
    <div id="page-content-wrapper" class="w-100">

        <!-- NAVBAR -->
        <nav class="navbar px-3 d-flex align-items-center justify-content-between"
             style="background: #0f172a; height: 60px;">

            <!-- BOTÓN SIDEBAR -->
            <button class="btn text-white" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- DERECHA -->
            <div class="d-flex align-items-center gap-3">

                <!-- 🔔 Notificación -->
                <i class="fa-regular fa-bell text-white"></i>

                <!-- 👤 Usuario -->
                <div class="dropdown">
                    <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-white"
                       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width:35px;height:35px;font-size:14px;">
                            <?php echo strtoupper(substr($_SESSION['nombre'],0,2)); ?>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="<?php echo URLROOT; ?>auth/logout">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>

        <!-- CONTENIDO INTERNO -->
        <div class="container-fluid p-4">