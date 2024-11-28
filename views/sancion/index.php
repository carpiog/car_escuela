<div class="container-fluid px-4">
    <h1 class="text-center">Registro de Sanciones</h1>

    <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-body">
                    <form id="formSancion">
                        <input type="hidden" name="san_id" id="san_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="san_catalogo" class="form-label">Alumno</label>
                                <select class="form-select" name="san_catalogo" id="san_catalogo" required>
                                    <option value="">Seleccione un alumno</option>
                                    <?php foreach ($alumnos as $alumno): ?>
                                        <option value="<?= htmlspecialchars($alumno['alu_id']) ?>">
                                            <?= htmlspecialchars($alumno['rango_nombre'] . ' - ' . $alumno['nombres_completos']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="san_fecha_sancion" class="form-label">Fecha de Sanción</label>
                                <input type="date" class="form-control" name="san_fecha_sancion" id="san_fecha_sancion" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="san_falta_id" class="form-label">Motivo</label>
                                <select class="form-select" name="san_falta_id" id="san_falta_id" required>
                                    <option value="">Seleccione una falta</option>
                                    <?php foreach ($faltas as $falta): ?>
                                        <option value="<?= htmlspecialchars($falta['fal_id']) ?>"
                                            data-horas="<?= htmlspecialchars($falta['fal_horas_arresto']) ?>"
                                            data-demeritos="<?= htmlspecialchars($falta['fal_demeritos']) ?>">
                                            <?= htmlspecialchars($falta['tipo_nombre'] . ' - ' . $falta['fal_descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="san_instructor_ordena" class="form-label">Instructor que Ordena</label>
                                <select class="form-select" name="san_instructor_ordena" id="san_instructor_ordena" required>
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
                                <label for="san_horas_arresto" class="form-label">Horas de Arresto</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="san_horas_arresto" id="san_horas_arresto" readonly>
                                    <span class="input-group-text">Horas</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="san_demeritos" class="form-label">Cantidad de Deméritos</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="san_demeritos" id="san_demeritos" readonly>
                                    <span class="input-group-text">Deméritos</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="san_observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="san_observaciones" id="san_observaciones" rows="3"></textarea>
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

    <h2 class="text-center">Lista de Arrestos</h2>

    <div class="row justify-content-center mb-3">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="startDate" class="form-label">Fecha Inicial</label>
                            <input type="date" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-4">
                            <label for="endDate" class="form-label">Fecha Final</label>
                            <input type="date" class="form-control" id="endDate">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" id="btnAplicarFiltro">
                                <i class="bi bi-filter"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tablaSancion">
                            <thead class="table-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Catálogo</th>
                                    <th>Grado Academico</th>
                                    <th>Rango</th>
                                    <th>Alumno</th>
                                    <th>Fecha Sanción</th>
                                    <th>Falta</th>
                                    <th>Horas Arresto</th>
                                    <th>Deméritos</th>
                                    <th>Ordeno</th>
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

<script src="<?= asset('build/js/sancion/index.js') ?>"></script>