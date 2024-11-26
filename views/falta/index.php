<h1 class="text-center">
    <?php if($titulo === 'TODAS'): ?>
        Listado de Todas las Faltas
    <?php else: ?>
        Listado de Faltas <?= $titulo ?>
    <?php endif; ?>
</h1>

<div class="row mb-4">
    <div class="col table-responsive">
        <table class="table table-bordered table-hover w-100" id="tablaFalta">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Sanción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="<?= asset('./build/js/falta/index.js') ?>"></script>