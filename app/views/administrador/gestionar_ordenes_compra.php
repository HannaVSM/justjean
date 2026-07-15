<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?php echo $data['title']; ?></h3>
        <a href="<?php echo URLROOT; ?>administrador/crearOrdenCompra" class="btn btn-primary">
            <i class="fa-solid fa-plus me-2"></i>Crear Orden de Compra
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center"># Orden</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['ordenes'] as $orden) : ?>
                        <tr>
                            <td class="text-center fw-bold"><?php echo $orden->idOrdenCompra; ?></td>
                            <td><?php echo $orden->nombreProveedor; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($orden->fecha)); ?></td>
                            <td class="text-center"><span class="badge bg-info"><?php echo $orden->estado; ?></span></td>
                            <td class="text-center">
                                <a href="<?php echo URLROOT; ?>administrador/verDetalleOrden/<?php echo $orden->idOrdenCompra; ?>" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-eye"></i> Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>