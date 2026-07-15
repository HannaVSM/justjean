<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>
<div class="container-fluid px-4">

<h1 class="mb-4"><?php echo $data['title']; ?></h1>
<p>Bienvenido al panel de administración. Desde aquí podrás gestionar el inventario, los proveedores y generar reportes.</p>

<?php /* Patrón Observer: mostrar notificaciones generadas por NotificadorUI */ ?>
<?php if (!empty($data['notificaciones'])): ?>
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <strong><i class="fa-solid fa-bell me-2"></i>Notificaciones del sistema:</strong>
    <ul class="mb-0 mt-1">
        <?php foreach($data['notificaciones'] as $notif): ?>
            <?php if($notif['evento'] === 'STOCK_BAJO'): ?>
                <li>⚠️ Stock bajo: <strong><?php echo htmlspecialchars($notif['datos']['nombre']); ?></strong>
                    — Actual: <?php echo $notif['datos']['stockActual']; ?> /
                    Mínimo: <?php echo $notif['datos']['stockMinimo']; ?>
                    <small class="text-muted">(<?php echo $notif['hora']; ?>)</small>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>
<?php endif; ?>

<div class="row g-3 mt-2">

    <!-- INSUMOS -->
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-bar bg-primary"></div>
            <div class="kpi-body">
                <div class="kpi-title">INSUMOS REGISTRADOS</div>
                <div class="kpi-value"><?php echo $data['totalInsumos']; ?></div>
                <div class="kpi-sub">↑ 3 este mes</div>
            </div>
        </div>
    </div>

    <!-- ALERTAS -->
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-bar bg-danger"></div>
            <div class="kpi-body">
                <div class="kpi-title">ALERTAS STOCK BAJO</div>
                <div class="kpi-value text-danger"><?php echo $data['alertasStock']; ?></div>
                <div class="kpi-sub">Requieren atención</div>
            </div>
        </div>
    </div>

    <!-- PROVEEDORES -->
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-bar bg-success"></div>
            <div class="kpi-body">
                <div class="kpi-title">PROVEEDORES</div>
                <div class="kpi-value"><?php echo $data['totalProveedores']; ?></div>
                <div class="kpi-sub">2 con orden activa</div>
            </div>
        </div>
    </div>

    <!-- PEDIDOS -->
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="kpi-bar bg-warning"></div>
            <div class="kpi-body">
                <div class="kpi-title">PEDIDOS ACTIVOS</div>
                <div class="kpi-value"><?php echo $data['pedidosActivos']; ?></div>
                <div class="kpi-sub">3 en producción</div>
            </div>
        </div>
    </div>

</div>
<div class="card shadow-sm mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Movimientos recientes</strong>
        <a href="#" class="small text-primary">Ver todos</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Insumo</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Operario</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['movimientos'] as $m) : ?>
                <tr>
                    <td><?php echo $m->insumo; ?></td>

                    <td>
                        <?php if($m->tipoMovimiento == 'entrada'): ?>
                            <span class="badge bg-primary-subtle text-primary">Entrada</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger">Salida</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <strong class="<?php echo $m->tipoMovimiento == 'entrada' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo ($m->tipoMovimiento == 'entrada' ? '+' : '-') . $m->cantidad; ?>
                        </strong>
                    </td>

                    <td><?php echo $m->operario; ?></td>

                    <td class="text-muted small">
                        <?php echo date('H:i', strtotime($m->fecha)); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<?php /* Patrón Iterator: detalle de insumos bajo stock mínimo */ ?>
<?php if (!empty($data['alertasDetalle'])): ?>
<div class="card border-left-danger shadow mt-2">
    <div class="card-header bg-danger text-white">
        <i class="fa-solid fa-exclamation-triangle me-2"></i>
        Insumos con Stock Bajo (<?php echo count($data['alertasDetalle']); ?>)
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead><tr><th>Insumo</th><th class="text-center">Stock Actual</th><th class="text-center">Mínimo</th></tr></thead>
            <tbody>
                <?php foreach($data['alertasDetalle'] as $ins): ?>
                <tr class="table-danger">
                    <td><?php echo htmlspecialchars($ins->nombre); ?></td>
                    <td class="text-center fw-bold text-danger"><?php echo $ins->stockActual; ?></td>
                    <td class="text-center"><?php echo $ins->stockMinimo; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>