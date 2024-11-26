<div class="bg-light min-vh-100 py-5">
    <div class="container">
        <h1 class="display-4 text-center text-dark mb-5">
            Estadísticas de Sanciones
        </h1>

        <div class="d-flex justify-content-center mb-5">
            <button
                id="actualizar"
                class="btn btn-primary btn-lg px-5 py-3 rounded-3 shadow-sm">
                Actualizar
            </button>
        </div>

        <div class="row g-4">
            <!-- Sanciones por Tipo -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 text-center text-dark mb-4">
                            Sanciones por Tipo
                        </h3>
                        <div class="h-100">
                            <canvas id="chartTipos"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sanciones por Grado -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 text-center text-dark mb-4">
                            Sanciones por Grado
                        </h3>
                        <div class="h-100">
                            <canvas id="chartGrados"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tendencias Mensuales de Sanciones -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 text-center text-dark mb-4">
                            Tendencias Mensuales de Sanciones
                        </h3>
                        <div class="h-100">
                            <canvas id="chartTendencias"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Faltas Más Frecuentes -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="h5 text-center text-dark mb-4">
                            Faltas Más Frecuentes
                        </h3>
                        <div class="h-100">
                            <canvas id="chartFaltas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>