<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-primary">Registro de Sanciones de Alumnos por Grado</h1>
            <h4 class="text-muted">FALTAS DISCIPLINARIAS, SANCIONES Y DEMÉRITOS</h4>
        </div>
    </div>
    <!-- Filter Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-funnel-fill text-primary h4 mb-0 me-2"></i>
                        <h5 class="mb-0 fw-bold">Filtros de Búsqueda</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="filtroGrado" class="form-label fw-bold">
                                <i class="bi bi-mortarboard me-1"></i>Grado Académico:
                            </label>
                            <select class="form-select form-select-lg shadow-sm" id="filtroGrado">
                                <option value="" selected>Todos los grados</option>
                                <?php foreach ($grados as $grado): ?>
                                    <option value="<?= $grado['gra_id'] ?>"><?= $grado['gra_nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-journal-plus text-primary h4 mb-0 me-2"></i>
                        <h5 class="mb-0 fw-bold">Registro de Sanción</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formSancion" class="needs-validation" novalidate>
                        <input type="hidden" name="san_id" id="san_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="san_catalogo" class="form-label fw-bold">Alumno</label>
                                <select class="form-select form-select-lg shadow-sm" name="san_catalogo" id="san_catalogo" required>
                                    <option value="">Seleccione un alumno</option>
                                    <?php foreach ($alumnos as $alumno): ?>
                                        <option value="<?= htmlspecialchars($alumno['alu_id']) ?>">
                                            <?= htmlspecialchars($alumno['rango_nombre'] . ' - ' . $alumno['nombres_completos']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Seleccione un alumno</div>
                            </div>
                            <div class="col-md-6">
                                <label for="san_fecha_sancion" class="form-label fw-bold">Fecha de Sanción</label>
                                <input type="date" class="form-select form-select-lg shadow-sm" name="san_fecha_sancion" id="san_fecha_sancion" required>
                                <div class="invalid-feedback">Seleccione una fecha</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="san_falta_id" class="form-label fw-bold">Motivo</label>
                                <select class="form-select form-select-lg shadow-sm" name="san_falta_id" id="san_falta_id" required>
                                    <option value="">Seleccione una falta</option>
                                    <?php foreach ($faltas as $falta): ?>
                                        <option value="<?= htmlspecialchars($falta['fal_id']) ?>"
                                            data-horas="<?= htmlspecialchars($falta['fal_horas_arresto']) ?>"
                                            data-demeritos="<?= htmlspecialchars($falta['fal_demeritos']) ?>">
                                            <?= htmlspecialchars($falta['tipo_nombre'] . ' - ' . $falta['fal_descripcion']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Seleccione un motivo de falta</div>
                            </div>
                            <div class="col-md-6">
                                <label for="san_instructor_ordena" class="form-label fw-bold">Instructor que Ordena</label>
                                <select class="form-select form-select-lg shadow-sm" name="san_instructor_ordena" id="san_instructor_ordena" required>
                                    <option value="">Seleccione un instructor</option>
                                    <?php foreach ($instructores as $instructor): ?>
                                        <option value="<?= htmlspecialchars($instructor['ins_id']) ?>">
                                            <?= htmlspecialchars($instructor['grado_arma'] . ' - ' . $instructor['nombres_apellidos']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Seleccione un instructor</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="san_horas_arresto" class="form-label fw-bold">Horas de Arresto</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg shadow-sm" name="san_horas_arresto" id="san_horas_arresto" readonly>
                                    <span class="input-group-text">Horas</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="san_demeritos" class="form-label fw-bold">Cantidad de Deméritos</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg shadow-sm" name="san_demeritos" id="san_demeritos" readonly>
                                    <span class="input-group-text">Deméritos</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="san_observaciones" class="form-label fw-bold">Observaciones</label>
                            <textarea class="form-control shadow-sm" name="san_observaciones" id="san_observaciones" rows="3"></textarea>
                        </div>

                        <div class="row g-3 text-center">
                            <div class="col-md-4 mx-auto" id="divGuardar">
                                <button type="submit" id="btnGuardar" class="btn btn-primary w-100 shadow-sm">
                                    <i class="bi bi-save me-2"></i>Guardar
                                </button>
                            </div>
                        </div>
                        <div class="row g-3 text-center mt-2">
                            <div class="col-md-4 mx-auto" id="divModificar" style="display: none;">
                                <button type="button" id="btnModificar" class="btn btn-warning w-100 shadow-sm">
                                    <i class="bi bi-pencil-square me-2"></i>Modificar
                                </button>
                            </div>
                            <div class="col-md-4 mx-auto" id="divCancelar" style="display: none;">
                                <button type="button" id="btnCancelar" class="btn btn-danger w-100 shadow-sm">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-filter text-primary h4 mb-0 me-2"></i>
                        <h5 class="mb-0 fw-bold">Filtros de Búsqueda</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="startDate" class="form-label fw-bold">
                                <i class="bi bi-calendar-check me-1"></i>Fecha Inicial
                            </label>
                            <input type="date" class="form-select form-select-lg shadow-sm" id="startDate">
                        </div>
                        <div class="col-md-4">
                            <label for="endDate" class="form-label fw-bold">
                                <i class="bi bi-calendar-x me-1"></i>Fecha Final
                            </label>
                            <input type="date" class="form-select form-select-lg shadow-sm" id="endDate">
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button class="btn btn-primary w-100 shadow-sm" id="btnAplicarFiltro">
                                <i class="bi bi-search me-2"></i>Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-table text-primary h4 mb-0 me-2"></i>
                <h5 class="mb-0 fw-bold">Lista de Arrestos</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tablaSancion">
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

<style>
    .table {
        font-size: 0.95rem;
    }
    
    .card {
        border-radius: 0.5rem;
    }

    .form-select:focus, .btn:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>

<script src="<?= asset('build/js/sancion/index.js') ?>"></script>