<div class="container-fluid py-4">
  <!-- Header -->
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-5 fw-bold text-primary mb-3">Promedio de Deméritos por Grado Academico</h1>
    </div>
  </div>

  <!-- Filters -->
  <div class="row mb-4 justify-content-center">
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <label class="form-label fw-bold small">
            <i class="bi bi-mortarboard me-1"></i>Grado
          </label>
          <select class="form-select form-select-lg" id="filtroGrado">
            <option value="">Todos los grados</option>
            <option value="BASICO">Básicos</option>
            <option value="BACHILLERATO">Diversificado</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <label class="form-label fw-bold small">
            <i class="bi bi-star me-1"></i>Conducta
          </label>
          <select class="form-select form-select-lg" id="filtroConducta">
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
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <?php if (!empty($estadisticas)): ?>
      <?php foreach ($estadisticas as $est): ?>
        <div class="col-xl-4 col-md-6 mb-4">
          <div class="card shadow border-0 h-100">
            <div class="card-body">
              <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                  <div class="bg-primary bg-gradient p-3 rounded-circle">
                    <i class="bi bi-graph-up text-white h4 mb-0"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <h5 class="fw-bold text-primary mb-1"><?= htmlspecialchars($est['gra_nombre']) ?></h5>
                  <p class="small text-muted mb-0">Total Alumnos: <?= htmlspecialchars($est['total_alumnos']) ?></p>
                </div>
              </div>
              
              <div class="border-top pt-3">
                <p class="fw-bold mb-2">Promedio Deméritos: <?= htmlspecialchars($est['promedio_demeritos']) ?></p>
                <div class="d-flex flex-wrap gap-2">
                  <span class="badge bg-success">Excelente: <?= htmlspecialchars($est['excelente']) ?></span>
                  <span class="badge bg-info">Muy Buena: <?= htmlspecialchars($est['muy_buena']) ?></span>
                  <span class="badge bg-primary">Buena: <?= htmlspecialchars($est['buena']) ?></span>
                  <span class="badge bg-warning">Regular: <?= htmlspecialchars($est['regular']) ?></span>
                  <span class="badge bg-danger">Deficiente: <?= htmlspecialchars($est['deficiente']) ?></span>
                  <span class="badge bg-danger">Mala: <?= htmlspecialchars($est['mala']) ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center shadow-sm border-0">
          <i class="bi bi-info-circle me-2"></i>No hay datos estadísticos disponibles.
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Demerits Table -->
  <div class="card shadow border-0">
    <div class="card-header bg-light py-3">
      <h5 class="mb-0">
        <i class="bi bi-table me-2"></i>Deméritos por Alumno
      </h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tablaDemerito">
          <thead class="table-light">
            <tr>
              <th class="fw-bold">No.</th>
              <th class="fw-bold">Catálogo</th>
              <th class="fw-bold">Grado</th>
              <th class="fw-bold">Rango</th>
              <th class="fw-bold">Alumno</th>
              <th class="fw-bold">Deméritos Acumulados</th>
              <th class="fw-bold">Conducta</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <i class="bi bi-bell me-2"></i>
      <strong class="me-auto">Notificación</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body"></div>
  </div>
</div>

<style>
.card {
  border-radius: 0.5rem;
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-5px);
}
.table th {
  white-space: nowrap;
}
.badge {
  font-weight: 500;
}
</style>

<script src="<?= asset('build/js/demerito/index.js') ?>"></script>