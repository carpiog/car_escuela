<div class="bg-gradient min-vh-100 py-5" style="background-color: #f8f9fa;">
  <div class="container">
    <div class="row mb-5">
      <div class="col-12 text-center">
        <h1 class="display-4 fw-bold text-primary">Estadísticas de Sanciones</h1>
        <p class="text-muted">Panel de control y análisis de sanciones</p>
      </div>
    </div>

    <div class="d-flex justify-content-center mb-5">
      <button id="actualizar" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-lg">
        <i class="bi bi-arrow-clockwise me-2"></i>Actualizar Datos
      </button>
    </div>

    <div class="row g-4">
      <!-- Sanciones por Tipo -->
      <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-lg h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
              <i class="bi bi-pie-chart-fill text-primary h3 mb-0 me-3"></i>
              <h3 class="h5 fw-bold text-dark mb-0">Sanciones por Tipo</h3>
            </div>
            <div style="height: 300px;">
              <canvas id="chartTipos"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Sanciones por Grado -->
      <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-lg h-100">
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
              <i class="bi bi-bar-chart-fill text-primary h3 mb-0 me-3"></i>
              <h3 class="h5 fw-bold text-dark mb-0">Sanciones por Grado</h3>
            </div>
            <div style="height: 300px;">
              <canvas id="chartGrados"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Faltas Más Frecuentes -->
      <div class="col-12">
        <div class="card border-0 shadow-lg">
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
              <i class="bi bi-graph-up text-primary h3 mb-0 me-3"></i>
              <h3 class="h5 fw-bold text-dark mb-0">Faltas Más Frecuentes</h3>
            </div>
            <div style="height: 400px;">
              <canvas id="chartFaltas"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.card {
  border-radius: 1rem;
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-5px);
}
.btn-primary {
  transition: all 0.3s;
}
.btn-primary:hover {
  transform: scale(1.05);
}
</style>

<script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>