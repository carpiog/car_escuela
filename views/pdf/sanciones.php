<div class='titulo'>REPORTE DE SANCIONES</div>

<div class='subtitulo'>Información del Alumno:</div>
<div class='info-alumno'>Catálogo: <?php echo $alumnoInfo['alu_catalogo']; ?></div>
<div class='info-alumno'>Nombre: <?php echo $alumnoInfo['alumno_nombre']; ?></div>
<div class='info-alumno'>Grado: <?php echo $alumnoInfo['gra_nombre']; ?></div>
<div class='info-alumno'>Rango: <?php echo $alumnoInfo['ran_nombre']; ?></div>

<div class='subtitulo'>Historial de Sanciones:</div>
<table>
    <thead>
        <tr>
            <th width='15%'>Fecha</th>
            <th width='20%'>Tipo</th>
            <th width='25%'>Categoría</th>
            <th width='25%'>Descripción</th>
            <th width='15%'>Sanción</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $totalDemeritos = 0;
        $totalHorasArresto = 0;
        
        foreach ($datos as $sancion): 
            if ($sancion['san_fecha_sancion']):
                $fecha = date('d/m/Y', strtotime($sancion['san_fecha_sancion']));
                $sancionTexto = $sancion['san_horas_arresto'] ? 
                    "{$sancion['san_horas_arresto']} horas" : 
                    "{$sancion['san_demeritos']} deméritos";
                
                $totalDemeritos += (int)$sancion['san_demeritos'];
                $totalHorasArresto += (int)$sancion['san_horas_arresto'];
        ?>
            <tr>
                <td class='text-center'><?php echo $fecha; ?></td>
                <td class='text-left'><?php echo $sancion['tipo_falta']; ?></td>
                <td class='text-left'><?php echo $sancion['categoria_falta']; ?></td>
                <td class='text-left'><?php echo $sancion['fal_descripcion']; ?></td>
                <td class='text-center'><?php echo $sancionTexto; ?></td>
            </tr>
        <?php 
            endif;
        endforeach; 
        ?>
    </tbody>
</table>

<div style='margin-top: 20px;' class="text-align-center">
    <div class='info-alumno'><strong>Total Deméritos:</strong> <?php echo $totalDemeritos; ?></div>
    <div class='info-alumno'><strong>Total Horas de Arresto:</strong> <?php echo $totalHorasArresto; ?></div>
</div>
