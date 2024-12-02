<div class="container">
    <div class="header text-center mb-4">
        <h1>NÓMINA DE ARRESTOS</h1>
        <p>Fecha de Generación: <?= date('d/m/Y', strtotime($fecha)) ?></p>
    </div>

    <table class="table-bordered w-100">
        <thead>
            <tr class="bg-light">
                <th class="text-center">No.</th>
                <th>Catálogo</th>
                <th>Grado</th>
                <th>Rango</th>
                <th>Alumno</th>
                <th class="text-center">Total Horas</th>
                <th class="text-center">Cumplidas</th>
                <th class="text-center">Pendientes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arrestos as $index => $arresto): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= $arresto['alu_catalogo'] ?></td>
                <td><?= $arresto['gra_nombre'] ?></td>
                <td><?= $arresto['ran_nombre'] ?></td>
                <td><?= $arresto['alumno_nombre'] ?></td>
                <td class="text-center"><?= round($arresto['total_horas_arresto']) ?></td>
                <td class="text-center"><?= round($arresto['horas_cumplidas']) ?></td>
                <td class="text-center"><?= round($arresto['horas_pendientes']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="firmas mt-5">
        <div class="firma text-center">
            <div class="linea">____________________</div>
            <p>DIRECTOR DE LA ESCUELA REGIONAL</p>
        </div>
    </div>
</div>