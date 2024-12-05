<div class="container">
    <h1 class="text-center">MEJORES CONDUCTAS GENERALES</h1>
    <p class="text-end">Fecha: <?= $fecha ?></p>

    <table class="table table-bordered mt-4">
        <thead class="bg-primary text-white">
            <tr>
                <th>No.</th>
                <th>Catálogo</th>
                <th>Grado</th>
                <th>Rango</th>
                <th>Alumno</th>
                <th>Total Sanciones</th>
                <th>Total Deméritos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($conductas as $index => $conducta): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $conducta['alu_catalogo'] ?></td>
                <td><?= $conducta['gra_nombre'] ?></td>
                <td><?= $conducta['ran_nombre'] ?></td>
                <td><?= $conducta['alumno_nombre'] ?></td>
                <td class="text-center"><?= $conducta['total_sanciones'] ?></td>
                <td class="text-center"><?= $conducta['total_demeritos'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>