<?php require_once APPROOT . '../app/views/layouts/header.php'; ?>

<div class="container-fluid p-4">

    <!-- HEADER -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa-solid fa-arrow-up text-danger me-2"></i>
                <?php echo $data['title']; ?>
            </h5>
        </div>

        <div class="card-body">

            <p class="text-muted">
                Descuenta insumos del inventario y asígnalos a un pedido específico.
            </p>

            <form action="<?php echo URLROOT; ?>operario/registrarSalida" method="POST" novalidate>

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
                                    (Stock: <?php echo $insumo->stockActual; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- CANTIDAD -->
                    <div class="col-md-6">
                        <label for="cantidad" class="form-label fw-semibold">
                            Cantidad a descontar
                        </label>
                        <input 
                            type="number" 
                            name="cantidad" 
                            id="cantidad" 
                            class="form-control" 
                            min="1" 
                            required 
                            aria-required="true"
                            placeholder="Ej: 10"
                        >
                    </div>

                    <!-- PEDIDO -->
                    <div class="col-md-6">
                        <label for="idPedido" class="form-label fw-semibold">
                            Pedido
                        </label>
                        <select 
                            name="idPedido" 
                            id="idPedido" 
                            class="form-select" 
                            required 
                            aria-required="true"
                        >
                            <option value="" disabled selected>Selecciona un pedido</option>
                            <?php foreach($data['pedidos'] as $pedido): ?>
                                <option value="<?php echo $pedido->idPedido; ?>">
                                    Pedido #<?php echo $pedido->idPedido; ?> - 
                                    <?php echo htmlspecialchars($pedido->cliente); ?>
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

                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-arrow-up me-2"></i>
                        Registrar salida
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once APPROOT . '../app/views/layouts/footer.php'; ?>