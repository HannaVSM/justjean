<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>
<div class="container-fluid px-4">
<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Insumos</h4>

        <!-- ESTE ES EL BOTÓN REAL -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="fa-solid fa-plus me-2"></i>Nuevo insumo
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <!-- BUSCADOR -->
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre o tipo...">
            </div>

            <!-- FILTROS -->
            <div class="mb-3 d-flex gap-2 flex-wrap">

                <button class="btn btn-sm btn-outline-primary filter-btn active" data-filter="todos">
                    Todos (<?php echo count($data['insumos']); ?>)
                </button>

                <?php 
                $tipos = [];
                foreach($data['insumos'] as $i){
                    $tipos[$i->tipo] = ($tipos[$i->tipo] ?? 0) + 1;
                }
                foreach($tipos as $tipo => $count): ?>
                    <button class="btn btn-sm btn-outline-secondary filter-btn" data-filter="<?php echo trim(strtolower($tipo)); ?>">
                        <?php echo $tipo; ?> (<?php echo $count; ?>)
                    </button>
                <?php endforeach; ?>

            </div>

            <!-- TABLA -->
            <div class="table-responsive">
                <table class="table align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Insumo</th>
                            <th>Tipo</th>
                            <th>Stock</th>
                            <th>Estado</th>
                            <th>Costo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="tablaInsumos">

                        <?php foreach($data['insumos'] as $insumo):

                            $porcentaje = $insumo->stockMinimo > 0 
                                ? ($insumo->stockActual / $insumo->stockMinimo) * 100 
                                : 100;

                            $estado = "Normal";
                            $color = "success";

                            if($insumo->stockActual < $insumo->stockMinimo){
                                $estado = "Crítico";
                                $color = "danger";
                            } elseif($insumo->stockActual < ($insumo->stockMinimo * 1.5)){
                                $estado = "Bajo";
                                $color = "warning";
                            }
                        ?>

                        <tr class="fila-insumo" data-tipo="<?php echo trim(strtolower($insumo->tipo)); ?>">

                            <!-- INSUMO -->
                            <td>
                                <strong><?php echo $insumo->nombre; ?></strong><br>
                                <small class="text-muted"><?php echo $insumo->descripcion; ?></small>
                            </td>

                            <!-- TIPO -->
                            <td><?php echo $insumo->tipo; ?></td>

                            <!-- STOCK -->
                            <td style="min-width:180px;">
                                <small><?php echo $insumo->stockActual; ?>/<?php echo $insumo->stockMinimo; ?></small>

                                <div class="progress mt-1" style="height:6px;">
                                    <div class="progress-bar bg-<?php echo $color; ?>" 
                                         style="width: <?php echo min($porcentaje,100); ?>%">
                                    </div>
                                </div>
                            </td>

                            <!-- ESTADO -->
                            <td>
                                <span class="badge bg-<?php echo $color; ?>-subtle text-<?php echo $color; ?>">
                                    <?php echo $estado; ?>
                                </span>
                            </td>

                            <!-- COSTO -->
                            <td>$<?php echo number_format($insumo->costo,0); ?></td>

                            <!-- ACCIONES -->
                            <td>
                                <div class="d-flex gap-2">

                                    <!-- EDITAR -->
                                    <button class="btn btn-sm btn-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditar"
                                        data-id="<?php echo $insumo->idInsumo; ?>"
                                        data-nombre="<?php echo $insumo->nombre; ?>"
                                        data-descripcion="<?php echo $insumo->descripcion; ?>"
                                        data-tipo="<?php echo $insumo->tipo; ?>"
                                        data-unidad="<?php echo $insumo->unidadMedida; ?>"
                                        data-stockactual="<?php echo $insumo->stockActual; ?>"
                                        data-stockminimo="<?php echo $insumo->stockMinimo; ?>"
                                        data-costo="<?php echo $insumo->costo; ?>"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    <!-- ELIMINAR -->
                                    <button class="btn btn-sm btn-light text-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar"
                                        data-id="<?php echo $insumo->idInsumo; ?>"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>

                                </div>
                            </td>

                        </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
</div>
<script>

// BUSCADOR
document.getElementById("searchInput").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll(".fila-insumo").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});

// FILTROS
document.querySelectorAll(".filter-btn").forEach(btn => {
    btn.addEventListener("click", function(){

        // Activar botón
        document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
        this.classList.add("active");

        const filtro = this.dataset.filter;

        document.querySelectorAll(".fila-insumo").forEach(row => {
            const tipo = row.dataset.tipo;

            if(filtro === "todos"){
                row.style.display = "";
            } else {
                row.style.display = (tipo === filtro) ? "" : "none";
            }
        });

    });
});

</script>