<div class="container-fluid px-4">
    <h1 class="text-center">Control de Cumplimiento de Arrestos</h1>

    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-body">
                    <form id="formCumplimiento">
                        <input type="hidden" name="cum_id" id="cum_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cum_sancion_id" class="form-label">Sanción</label>
                                <select class="form-select" name="cum_sancion_id" id="cum_sancion_id" required>
                                    <option value="">Seleccione una sanción</option>
                                    <?php foreach ($sanciones as $sancion): ?>
                                        <option value="<?= htmlspecialchars($sancion['san_id']) ?>" 
                                                data-horas="<?= htmlspecialchars($sancion['san_horas_arresto']) ?>">
                                            <?= htmlspecialchars($sancion['ran_nombre'] . ' - ' . $sancion['alumno_nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cum_fecha" class="form-label">Fecha de Cumplimiento</label>
                                <input type="date" class="form-control" name="cum_fecha" id="cum_fecha" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cum_estado" class="form-label">Estado</label>
                                <select class="form-select" name="cum_estado" id="cum_estado" required>
                                    <option value="P">Pendiente</option>
                                    <option value="C">Cumplió</option>
                                    <option value="T">Traslado</option>
                                    <option value="F">Faltó</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="cum_instructor_supervisa" class="form-label">Instructor que Supervisa</label>
                                <select class="form-select" name="cum_instructor_supervisa" id="cum_instructor_supervisa" required>
                                    <option value="">Seleccione un instructor</option>
                                    <?php foreach ($instructores as $instructor): ?>
                                        <option value="<?= htmlspecialchars($instructor['ins_id']) ?>">
                                            <?= htmlspecialchars($instructor['grado_arma'] . ' - ' . $instructor['nombres_apellidos']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cum_horas_cumplidas" class="form-label">Horas Cumplidas</label>
                                <input type="number" class="form-control" name="cum_horas_cumplidas" id="cum_horas_cumplidas" min="0">
                            </div>
                            <div class="col-md-6">
                                <label for="cum_horas_pendientes" class="form-label">Horas Pendientes</label>
                                <input type="number" class="form-control" name="cum_horas_pendientes" id="cum_horas_pendientes" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cum_fin_semana_inicio" class="form-label">Inicio de Fin de Semana</label>
                                <input type="date" class="form-control" name="cum_fin_semana_inicio" id="cum_fin_semana_inicio">
                            </div>
                            <div class="col-md-6">
                                <label for="cum_fin_semana_siguiente" class="form-label">Siguiente Fin de Semana</label>
                                <input type="date" class="form-control" name="cum_fin_semana_siguiente" id="cum_fin_semana_siguiente">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="cum_observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="cum_observaciones" id="cum_observaciones" rows="3"></textarea>
                        </div>

                        <div class="row g-3 text-center d-flex justify-content-center mb-3">
                            <div class="col-md-4" id="divGuardar">
                                <button type="submit" id="btnGuardar" class="btn btn-primary w-100">
                                    <i class="bi bi-save"></i> Guardar
                                </button>
                            </div>
                        </div>
                        <div class="row g-3 text-center d-flex justify-content-center mb-3">
                            <div class="col-md-4" id="divModificar" style="display: none;">
                                <button type="button" id="btnModificar" class="btn btn-warning w-100">
                                    <i class="bi bi-pencil-square"></i> Modificar
                                </button>
                            </div>
                            <div class="col-md-4" id="divCancelar" style="display: none;">
                                <button type="button" id="btnCancelar" class="btn btn-danger w-100">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-center">Lista de Cumplimientos de Arrestos</h2>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tablaCumplimiento">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Alumno</th>
                                    <th>Grado</th>
                                    <th>Rango</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Horas Cumplidas</th>
                                    <th>Horas Pendientes</th>
                                    <th>Instructor Supervisa</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/cumplimiento/index.js') ?>"></script>