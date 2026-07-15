<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?php echo $data['title']; ?></h3>
        <a href="<?php echo URLROOT; ?>administrador/gestionarOrdenesCompra" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Volver al Listado
        </a>
    </div>
    <div class="card-body">
        
        <fieldset class="border p-3 mb-4">
            <legend class="w-auto px-2 fs-5">Datos de la Orden</legend>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Proveedor:</strong> <?php echo $data['orden']->nombreProveedor; ?></p>
                </div>
                <div class="col-md-3">
                    <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($data['orden']->fecha)); ?></p>
                </div>
                <div class="col-md-3">
                    <p><strong>Estado:</strong> <span class="badge bg-info"><?php echo $data['orden']->estado; ?></span></p>
                </div>
            </div>
        </fieldset>

        <h4 class="mb-3">Insumos Solicitados</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Insumo</th>
                        <th class="text-center">Cantidad Solicitada</th>
                        <th class="text-end">Costo Unitario</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_orden = 0;
                        foreach($data['detalles'] as $detalle) : 
                        $subtotal = $detalle->cantidadSolicitada * $detalle->costoUnitario;
                        $total_orden += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo $detalle->nombreInsumo; ?></td>
                            <td class="text-center"><?php echo $detalle->cantidadSolicitada; ?> <?php echo $detalle->unidadMedida; ?></td>
                            <td class="text-end">$<?php echo number_format($detalle->costoUnitario, 2); ?></td>
                            <td class="text-end">$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-dark">
                        <td colspan="3" class="text-end fw-bold">TOTAL DE LA ORDEN</td>
                        <td class="text-end fw-bold">$<?php echo number_format($total_orden, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</div>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>