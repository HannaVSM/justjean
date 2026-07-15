<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0"><?php echo $data['title']; ?></h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="fa-solid fa-plus me-2"></i>Crear Proveedor
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['proveedores'] as $proveedor) : ?>
                        <tr>
                            <td><?php echo $proveedor->nombre; ?></td>
                            <td><?php echo $proveedor->contacto; ?></td>
                            <td><?php echo $proveedor->email; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditar" 
                                        data-id="<?php echo $proveedor->idProveedor; ?>" 
                                        data-nombre="<?php echo $proveedor->nombre; ?>" 
                                        data-contacto="<?php echo $proveedor->contacto; ?>" 
                                        data-email="<?php echo $proveedor->email; ?>">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                        data-id="<?php echo $proveedor->idProveedor; ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCrearLabel">Crear Nuevo Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URLROOT; ?>administrador/gestionarProveedores" method="POST">
                <input type="hidden" name="action" value="crear">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="contacto" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="contacto" name="contacto">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URLROOT; ?>administrador/gestionarProveedores" method="POST">
                <input type="hidden" name="action" value="editar">
                <input type="hidden" id="edit_id" name="idProveedor">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contacto" class="form-label">Contacto</label>
                        <input type="text" class="form-control" id="edit_contacto" name="contacto">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">Eliminar Proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo URLROOT; ?>administrador/gestionarProveedores" method="POST">
                <input type="hidden" name="action" value="eliminar">
                <input type="hidden" id="delete_id" name="idProveedor">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este proveedor? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para pasar datos al modal de edición
    const modalEditar = document.getElementById('modalEditar');
    modalEditar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        // Extraer datos de los atributos data-*
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        const contacto = button.getAttribute('data-contacto');
        const email = button.getAttribute('data-email');
        
        // Actualizar los campos del formulario en el modal
        modalEditar.querySelector('#edit_id').value = id;
        modalEditar.querySelector('#edit_nombre').value = nombre;
        modalEditar.querySelector('#edit_contacto').value = contacto;
        modalEditar.querySelector('#edit_email').value = email;
    });

    // Script para pasar el ID al modal de eliminación
    const modalEliminar = document.getElementById('modalEliminar');
    modalEliminar.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        modalEliminar.querySelector('#delete_id').value = id;
    });
</script>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>