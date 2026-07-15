<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container-fluid p-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0"><?php echo $data['title']; ?></h4>
            <div class="text-muted small">Asocia materiales a pedidos de producción</div>
        </div>
    </div>

    <div class="card shadow-sm form-card">

        <div class="card-body">

            <form action="<?php echo URLROOT; ?>operario/vincularInsumoPedido" method="POST" novalidate>

                <!-- PEDIDO -->
                <div class="mb-4">
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
                        <option value="">Selecciona un pedido</option>
                        <?php foreach($data['pedidos'] as $pedido): ?>
                            <option value="<?php echo $pedido->idPedido; ?>">
                                Pedido #<?php echo $pedido->idPedido; ?> - <?php echo htmlspecialchars($pedido->cliente); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-text">
                        Selecciona el pedido al que deseas asignar insumos
                    </div>
                </div>

                <!-- INSUMO + CANTIDAD -->
                <div class="row g-3">

                    <!-- INSUMO -->
                    <div class="col-md-6">
                        <label for="idInsumo" class="form-label fw-semibold">
                            Insumo requerido
                        </label>

                        <select 
                            name="idInsumo" 
                            id="idInsumo" 
                            class="form-select"
                            required
                            aria-required="true"
                        >
                            <option value="">Selecciona un insumo</option>
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
                            Cantidad requerida
                        </label>

                        <input 
                            type="number" 
                            name="cantidad" 
                            id="cantidad" 
                            class="form-control"
                            min="1"
                            required
                            aria-required="true"
                            aria-describedby="cantidadHelp"
                        >

                        <div id="cantidadHelp" class="form-text">
                            Ingresa la cantidad necesaria para el pedido
                        </div>
                    </div>

                </div>

                <!-- BOTÓN -->
                <div class="mt-4 d-flex justify-content-end">
                    <button 
                        type="submit" 
                        class="btn btn-primary px-4"
                        aria-label="Vincular insumo al pedido seleccionado"
                    >
                        <i class="fa-solid fa-link me-2"></i>
                        Vincular insumo
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>