<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-primary">
                Administración de Alumnos por Grado
            </h1>
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

    <!-- Table Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-table text-primary h4 mb-0 me-2"></i>
                <h5 class="mb-0 fw-bold">Listado de Alumnos</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaAlumno">
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    .table {
        font-size: 0.95rem;
    }
    
    .card {
        border-radius: 0.5rem;
    }

    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>

<script src="<?= asset('build/js/alugrado/index.js') ?>"></script>