<div class="container-fluid py-4">
   <div class="row mb-4">
       <div class="col-12 text-center">
           <h1 class="display-4 fw-bold text-primary">Manual de Usuario</h1>
           <p class="text-muted">Sistema de Control Académico</p>
       </div>
   </div>

   <div class="card shadow-lg border-0">
       <div class="card-header bg-white py-3">
           <div class="d-flex align-items-center">
               <i class="bi bi-book text-primary h4 mb-0 me-2"></i>
               <h5 class="mb-0 fw-bold">Documentación</h5>
           </div>
       </div>
       <div class="card-body">
           <div class="ratio ratio-16x9" style="height: 800px;">
               <embed src="<?= asset('./../public/pdf/manual.pdf') ?>" 
                      type="application/pdf" 
                      class="w-100 h-100 shadow-sm rounded"/>
           </div>
       </div>
   </div>
</div>

<script src="<?= asset('./build/js/manual/index.js') ?>"></script>