<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-5 fw-bold text-primary">
        <?php if($titulo === 'TODAS'): ?>
          Listado de Todas las Faltas
        <?php else: ?>
          Listado de Faltas <?= $titulo ?>
        <?php endif; ?>
      </h1>
    </div>
  </div>

  <div class="card border-0 shadow-lg">
    <div class="card-header bg-white py-3">
      <div class="d-flex align-items-center">
        <i class="bi bi-list-check text-primary h4 mb-0 me-2"></i>
        <h5 class="mb-0 fw-bold">Registro de Faltas</h5>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover align-middle" id="tablaFalta">
          <thead>
            <tr class="table-dark text-white">
              <th class="fw-semibold">No.</th>
              <th class="fw-semibold">Tipo</th>
              <th class="fw-semibold">Categoría</th>
              <th class="fw-semibold">Descripción</th>
              <th class="fw-semibold">Sanción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
.card { border-radius: 0.5rem; }
.dataTables_wrapper .dataTables_length, 
.dataTables_wrapper .dataTables_filter {
  margin-bottom: 1rem;
}
.dataTables_wrapper .dataTables_length select {
  min-width: 5rem;
  padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  border-radius: 0.25rem;
  border: 1px solid #dee2e6;
}
.dataTables_wrapper .dataTables_filter input {
  min-width: 300px;
  padding: 0.375rem 0.75rem;
  border-radius: 0.25rem;
  border: 1px solid #dee2e6;
  margin-left: 0.5rem;
}
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
  margin-top: 1rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
  padding: 0.375rem 0.75rem;
  margin: 0 0.25rem;
  border-radius: 0.25rem;
  border: 1px solid #dee2e6;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
  background: #0d6efd !important;
  border-color: #0d6efd !important;
  color: white !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: #e9ecef !important;
  border-color: #dee2e6 !important;
  color: #000 !important;
}
</style>

<script src="<?= asset('./build/js/falta/index.js') ?>"></script>