<!-- views/arresto/index.php -->
<div class="container-fluid py-4">
   <div class="row mb-4">
       <div class="col-12 text-center">
           <h1 class="display-5 fw-bold text-primary">Control de Horas de Arresto</h1>
           <h4 class="text-muted">SEGUIMIENTO DE SANCIONES</h4>
       </div>
   </div>

   <div class="card shadow-lg border-0">
       <div class="card-header bg-white py-3">
           <div class="d-flex align-items-center">
               <i class="bi bi-clock-history text-primary h4 mb-0 me-2"></i>
               <h5 class="mb-0 fw-bold">Listado de Arrestos</h5>
           </div>
       </div>
       <button type="button" id="btnGenerarNomina" class="btn btn-primary">
            <i class="bi bi-file-earmark-pdf-fill me-2"></i>
            Generar Nómina de Arresto
        </button>
       <div class="card-body">
           <div class="table-responsive">
               <table class="table table-hover table-striped align-middle" id="tablaArresto">
                   <thead class="table-light">
                       <tr>
                           <th>Catálogo</th>
                           <th>Grado</th>
                           <th>Rango</th>
                           <th>Alumno</th>
                           <th>Total Horas</th>
                           <th>Cumplidas</th>
                           <th>Pendientes</th>
                           <th>Estado</th>
                           <th>Acciones</th>
                       </tr>
                   </thead>
                   <tbody></tbody>
               </table>
           </div>
       </div>
   </div>
</div>

<div class="modal fade" id="modalHoras" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Horas Cumplidas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formHoras">
                    <input type="hidden" id="alumno_id">
                    <div class="mb-3">
                        <label class="form-label">Total Horas de Arresto</label>
                        <input type="text" class="form-control" id="total_horas" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horas Cumplidas</label>
                        <input type="number" class="form-control" id="horas_cumplidas" required min="0" step="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarHoras">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/arresto/index.js') ?>"></script>