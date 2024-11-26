<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Control de Deméritos</h1>
    
    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="filtroGrado" class="form-label">Filtrar por Grado</label>
            <select class="form-select" id="filtroGrado">
                <option value="">Todos los grados</option>
                <option value="BASICO">Básicos</option>
                <option value="BACHILLERATO">Diversificado</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filtroConducta" class="form-label">Filtrar por Conducta</label>
            <select class="form-select" id="filtroConducta">
                <option value="">Todas las conductas</option>
                <option value="EXCELENTE">Excelente</option>
                <option value="MUY BUENA">Muy Buena</option>
                <option value="BUENA">Buena</option>
                <option value="REGULAR">Regular</option>
                <option value="DEFICIENTE">Deficiente</option>
                <option value="MALA">Mala</option>
                <option value="RIESGO">En riesgo (Deficiente/Mala)</option>
            </select>
        </div>
    </div>
    
    <!-- Resumen Estadístico -->
    <div class="row mb-4">
        <?php if (!empty($estadisticas)): ?>
            <?php foreach ($estadisticas as $est): ?>
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-primary"><?= htmlspecialchars($est['gra_nombre']) ?></div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">
                                        Total Alumnos: <?= htmlspecialchars($est['total_alumnos']) ?>
                                    </div>
                                    <div class="h6 mb-0 text-gray-800">
                                        Promedio Deméritos: <?= htmlspecialchars($est['promedio_demeritos']) ?>
                                    </div>
                                    <div class="mt-2">
                                        <div class="mb-1">
                                            <span class="badge text-bg-success">Excelente: <?= htmlspecialchars($est['excelente']) ?></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="badge text-bg-info">Muy Buena: <?= htmlspecialchars($est['muy_buena']) ?></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="badge text-bg-primary">Buena: <?= htmlspecialchars($est['buena']) ?></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="badge text-bg-warning">Regular: <?= htmlspecialchars($est['regular']) ?></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="badge text-bg-danger">Deficiente: <?= htmlspecialchars($est['deficiente']) ?></span>
                                        </div>
                                        <div class="mb-1">
                                            <span class="badge text-bg-danger">Mala: <?= htmlspecialchars($est['mala']) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No hay datos estadísticos disponibles.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tabla de Deméritos -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Deméritos por Alumno
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tablaDemerito">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Catálogo</th>
                            <th>Grado</th>
                            <th>Rango</th>
                            <th>Alumno</th>
                            <th>Total Sanciones</th>
                            <th>Deméritos Acumulados</th>
                            <th>Conducta</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container para notificaciones -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notificación</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<!-- Scripts -->
<script src="<?= asset('build/js/demerito/index.js') ?>"></script>