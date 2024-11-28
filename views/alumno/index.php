<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold text-primary mb-4">Registro de Alumnos</h1>
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <form id="formAlumno" class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <input type="hidden" name="alu_id" id="alu_id">

                    <!-- Grado y Rango -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">
                                <i class="bi bi-stars me-1"></i>Grado
                            </label>
                            <select class="form-select form-select-lg" name="alu_grado_id" id="alu_grado_id" required>
                                <option value="">Seleccione un grado</option>
                                <?php foreach ($grados as $grado): ?>
                                    <option value="<?= $grado->gra_id ?>"><?= $grado->gra_nombre ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">
                                <i class="bi bi-shield me-1"></i>Rango
                            </label>
                            <select class="form-select form-select-lg" name="alu_rango_id" id="alu_rango_id" required>
                                <option value="">Seleccione un rango</option>
                                <?php foreach ($rangos as $rango): ?>
                                    <option value="<?= $rango->ran_id ?>"><?= $rango->ran_nombre ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nombres -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">
                                <i class="bi bi-person me-1"></i>Primer Nombre
                            </label>
                            <input type="text" class="form-control form-control-lg" name="alu_primer_nombre" id="alu_primer_nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Segundo Nombre</label>
                            <input type="text" class="form-control form-control-lg" name="alu_segundo_nombre" id="alu_segundo_nombre">
                        </div>
                    </div>

                    <!-- Apellidos -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">
                                <i class="bi bi-person me-1"></i>Primer Apellido
                            </label>
                            <input type="text" class="form-control form-control-lg" name="alu_primer_apellido" id="alu_primer_apellido" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Segundo Apellido</label>
                            <input type="text" class="form-control form-control-lg" name="alu_segundo_apellido" id="alu_segundo_apellido">
                        </div>
                    </div>

                    <!-- Catálogo -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">
                            <i class="bi bi-hash me-1"></i>Catálogo del Alumno
                        </label>
                        <input type="text" class="form-control form-control-lg" name="alu_catalogo" id="alu_catalogo"
                            required pattern="^\d{5}$" minlength="5" maxlength="5"
                            title="El catálogo debe contener exactamente 5 números">
                    </div>
                    <!-- Sexo -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">
                            <i class="bi bi-gender-ambiguous me-1"></i>Sexo
                        </label>
                        <select class="form-select form-select-lg" name="alu_sexo" id="alu_sexo" required>
                            <option value="">Seleccione el sexo</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <button type="submit" form="formAlumno" id="btnGuardar"
                                class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="btnModificar"
                                class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-pencil me-2"></i>Modificar
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="btnCancelar"
                                class="btn btn-danger btn-lg w-100">
                                <i class="bi bi-x-circle me-2"></i>Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-light py-3">
                    <h2 class="h5 text-primary mb-0 text-center">
                        <i class="bi bi-list-ul me-2"></i>Listado de Alumnos
                    </h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle w-100" id="tablaAlumno">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .card {
        border-radius: 0.5rem;
    }

    .table {
        font-size: 0.95rem;
    }
</style>
<script src="<?= asset('build/js/alumno/index.js') ?>"></script>