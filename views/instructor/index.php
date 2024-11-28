<div class="container py-5">
  <!-- Header -->
  <div class="row mb-5">
    <div class="col-12 text-center">
      <h1 class="display-5 fw-bold text-primary mb-4">Registro de Instructores</h1>
    </div>
  </div>

  <!-- Form -->
  <div class="row justify-content-center mb-5">
    <div class="col-lg-8">
      <form id="formInstructor" class="card shadow-lg border-0">
        <div class="card-body p-4">
          <input type="hidden" name="ins_id" id="ins_id">
          
          <!-- Catálogo -->
          <div class="mb-4">
            <label class="form-label fw-bold text-muted small">
              <i class="bi bi-hash me-1"></i>Catálogo del Oficial Instructor
            </label>
            <input type="number" class="form-control form-control-lg" name="ins_catalogo" id="ins_catalogo" required>
          </div>

          <!-- Buttons -->
          <div class="row g-3">
            <div class="col-md-4">
              <button type="submit" form="formInstructor" id="btnGuardar" 
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
            <i class="bi bi-list-ul me-2"></i>Listado de Instructores
          </h2>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tablaInstructor">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.form-control:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.card {
  border-radius: 0.5rem;
}

.card-form {
  max-width: 800px;
  margin: 0 auto;
}

.table {
  font-size: 0.95rem;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
  border-radius: 0.25rem;
  border: 1px solid #dee2e6;
  padding: 0.375rem 0.75rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: #0d6efd !important;
  border-color: #0d6efd !important;
  color: white !important;
}
</style>

<script src="<?= asset('./build/js/instructor/index.js') ?>"></script>