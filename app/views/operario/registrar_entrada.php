<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="container-fluid p-4">

    <!-- HEADER -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-arrow-down text-success me-2"></i>
                <?php echo $data['title']; ?>
            </h5>
        </div>

        <div class="card-body">

            <p class="text-muted">
                Registra los insumos recibidos de proveedores y actualiza el inventario.
            </p>

            <form action="<?php echo URLROOT; ?>operario/registrarEntrada" method="POST" novalidate>

                <div class="row g-3">

                    <!-- INSUMO -->
                    <div class="col-md-6">
                        <label for="idInsumo" class="form-label fw-semibold">
                            Insumo
                        </label>
                        <select 
                            name="idInsumo" 
                            id="idInsumo" 
                            class="form-select" 
                            required 
                            aria-required="true"
                        >
                            <option value="" disabled selected>Selecciona un insumo</option>
                            <?php foreach($data['insumos'] as $insumo): ?>
                                <option value="<?php echo $insumo->idInsumo; ?>">
                                    <?php echo htmlspecialchars($insumo->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- CANTIDAD -->
                    <div class="col-md-6">
                        <label for="cantidad" class="form-label fw-semibold">
                            Cantidad recibida
                        </label>
                        <input 
                            type="number" 
                            name="cantidad" 
                            id="cantidad" 
                            class="form-control" 
                            min="1" 
                            required 
                            aria-required="true"
                            placeholder="Ej: 50"
                        >
                    </div>

                    <!-- PROVEEDOR -->
                    <div class="col-md-6">
                        <label for="idProveedor" class="form-label fw-semibold">
                            Proveedor
                        </label>
                        <select 
                            name="idProveedor" 
                            id="idProveedor" 
                            class="form-select" 
                            required 
                            aria-required="true"
                        >
                            <option value="" disabled selected>Selecciona un proveedor</option>
                            <?php foreach($data['proveedores'] as $proveedor): ?>
                                <option value="<?php echo $proveedor->idProveedor; ?>">
                                    <?php echo htmlspecialchars($proveedor->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="<?php echo URLROOT; ?>operario/dashboard" class="btn btn-light border">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check me-2"></i>
                        Registrar entrada
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>