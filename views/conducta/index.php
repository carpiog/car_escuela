<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-primary">Conductas Generales y por Grados</h1>
            <h4 class="text-muted">REGISTRO DE CONDUCTAS Y DEMÉRITOS</h4>
        </div>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-list-stars text-primary h4 mb-0 me-2"></i>
                    <h5 class="mb-0 fw-bold">Lista de Conductas</h5>
                </div>
                <button class="btn btn-primary" id="btnImprimir">
                    <i class="bi bi-printer me-2"></i>Imprimir Reporte
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="tablaConductas">
                    <thead class="table-light">
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
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?= asset('./build/js/conducta/index.js') ?>"></script>