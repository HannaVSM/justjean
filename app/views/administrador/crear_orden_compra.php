<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h3 class="mb-0"><?php echo $data['title']; ?></h3>
    </div>
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>administrador/crearOrdenCompra" method="POST">
            
            <fieldset class="border p-3 mb-3">
                <legend class="w-auto px-2">Datos Generales</legend>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="idProveedor" class="form-label">Proveedor:</label>
                        <select name="idProveedor" id="idProveedor" class="form-select" required>
                            <option value="" disabled selected>-- Elige un proveedor --</option>
                            <?php foreach($data['proveedores'] as $proveedor): ?>
                                <option value="<?php echo $proveedor->idProveedor; ?>"><?php echo $proveedor->nombre; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha de la Orden:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border p-3">
                <legend class="w-auto px-2">Insumos a Solicitar</legend>
                <div class="table-responsive">
                    <table class="table" id="tablaInsumos">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Insumo</th>
                                <th style="width: 20%;">Cantidad</th>
                                <th style="width: 20%;">Costo Unitario</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            </tbody>
                    </table>
                </div>
                <button type="button" id="btnAnadirInsumo" class="btn btn-secondary"><i class="fa-solid fa-plus me-2"></i>Añadir Insumo</button>
            </fieldset>

            <div class="mt-4 d-flex justify-content-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save me-2"></i>Guardar Orden de Compra</button>
            </div>
        </form>
    </div>
</div>

<template id="filaInsumoTemplate">
    <tr>
        <td>
            <select name="insumos[]" class="form-select" required>
                <option value="" disabled selected>-- Elige un insumo --</option>
                <?php foreach($data['insumos'] as $insumo): ?>
                    <option value="<?php echo $insumo->idInsumo; ?>"><?php echo $insumo->nombre; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" name="cantidades[]" class="form-control" min="1" required></td>
        <td><input type="number" name="costos[]" class="form-control" step="0.01" min="0" required></td>
        <td><button type="button" class="btn btn-danger btn-sm btnQuitarInsumo"><i class="fa-solid fa-trash"></i></button></td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAnadirInsumo = document.getElementById('btnAnadirInsumo');
    const tablaInsumosBody = document.querySelector('#tablaInsumos tbody');
    const template = document.getElementById('filaInsumoTemplate');

    btnAnadirInsumo.addEventListener('click', function() {
        const clone = template.content.cloneNode(true);
        tablaInsumosBody.appendChild(clone);
    });

    tablaInsumosBody.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('btnQuitarInsumo')) {
            event.target.closest('tr').remove();
        }
    });
});
</script>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>