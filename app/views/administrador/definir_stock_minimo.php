<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h3 class="mb-0"><?php echo $data['title']; ?></h3>
    </div>
    <div class="card-body">
        <p>Ajusta el nivel mínimo de stock para cada insumo. Cuando el stock actual caiga por debajo de este nivel, se generará una alerta visual.</p>
        
        <form action="<?php echo URLROOT; ?>administrador/definirStockMinimo" method="POST">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre del Insumo</th>
                            <th class="text-center">Stock Actual</th>
                            <th style="width: 200px;" class="text-center">Nuevo Stock Mínimo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['insumos'] as $insumo) : ?>
                            <tr>
                                <td><?php echo $insumo->nombre; ?></td>
                                <td class="text-center align-middle"><?php echo $insumo->stockActual; ?></td>
                                <td>
                                    <input type="number" 
                                           class="form-control text-center" 
                                           name="stock_minimo[<?php echo $insumo->idInsumo; ?>]" 
                                           value="<?php echo $insumo->stockMinimo; ?>"
                                           min="0"
                                           required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-save me-2"></i>Guardar Todos los Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>