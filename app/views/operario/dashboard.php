<?php require_once APPROOT  . '../app/views/layouts/header.php'; ?>

<div class="container-fluid p-4">

    <!-- SALUDO -->
    <div class="mb-4">
        <div class="text-muted small">Buenos días,</div>
        <h4 class="fw-bold mb-0"><?php echo $_SESSION['nombre']; ?></h4>
    </div>

    <div class="stepper mb-4">

    <div class="step active">
        <div class="circle"><i class="fa-solid fa-check"></i></div>
        <span>Entrada</span>
    </div>

    <div class="line active"></div>

    <div class="step current">
        <div class="circle">2</div>
        <span>Vinculación</span>
    </div>

    <div class="line"></div>

    <div class="step">
        <div class="circle">3</div>
        <span>Salida</span>
    </div>

</div>

    <!-- CARDS DE ACCIONES -->
    <div class="row g-3 mb-4">

        <!-- Entrada -->
        <div class="col-md-4">
            <div class="action-card">
                <div class="icon bg-success-subtle text-success">
                    <i class="fa-solid fa-arrow-down"></i>
                </div>
                <h6>Registrar entrada</h6>
                <p class="text-muted small">Insumos recibidos de proveedor</p>
                <a href="<?php echo URLROOT ; ?>operario/registrarEntrada" class="btn btn-light border w-100">
                    Iniciar
                </a>
            </div>
        </div>

        <!-- Vincular -->
        <div class="col-md-4">
            <div class="action-card active">
                <div class="icon bg-primary-subtle text-primary">
                    <i class="fa-solid fa-link"></i>
                </div>
                <h6>Vincular a pedido</h6>
                <p class="text-muted small">Asignar insumos a producción</p>
                <a href="<?php echo URLROOT ; ?>operario/vincularInsumoPedido" class="btn btn-primary w-100">
                    Continuar →
                </a>
            </div>
        </div>

        <!-- Salida -->
        <div class="col-md-4">
            <div class="action-card">
                <div class="icon bg-danger-subtle text-danger">
                    <i class="fa-solid fa-arrow-up"></i>
                </div>
                <h6>Registrar salida</h6>
                <p class="text-muted small">Descontar del inventario</p>
                <a href="<?php echo URLROOT ; ?>operario/registrarSalida" class="btn btn-light border w-100">
                    Iniciar
                </a>
            </div>
        </div>

    </div>

    <!-- TAREAS (SIMULADAS PERO LISTAS PARA BACKEND) -->
    <?php
    $tareas = [
        ['tarea' => 'Vincular tela denim', 'pedido' => '#PED-041', 'estado' => 'Pendiente'],
        ['tarea' => 'Registrar salida hilos', 'pedido' => '#PED-039', 'estado' => 'En espera']
    ];
    ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Mis tareas de hoy</strong>
            <span class="badge bg-primary-subtle text-primary">
                <?php echo count($tareas); ?> pendientes
            </span>
        </div>

        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tarea</th>
                        <th>Pedido</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tareas as $t): ?>
                    <tr>
                        <td><?php echo $t['tarea']; ?></td>
                        <td><?php echo $t['pedido']; ?></td>
                        <td>
                            <?php if($t['estado'] == 'Pendiente'): ?>
                                <span class="badge bg-primary-subtle text-primary">Pendiente</span>
                            <?php else: ?>
                                <span class="badge bg-secondary-subtle text-secondary">En espera</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once APPROOT  . '../app/views/layouts/footer.php'; ?>