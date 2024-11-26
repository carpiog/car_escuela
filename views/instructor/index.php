<h1 class="text-center">Formulario para ingresar Oficiales Instructores</h1>
<div class="row justify-content-center mb-4">
    <form id="formInstructor" class="border shadow p-4 col-lg-4">
        <input type="hidden" name="ins_id" id="ins_id">
        <div class="mb-3 text-center">
            <label for="ins_catalogo">Catálogo del Oficial Instructor</label>
            <input type="number" name="ins_catalogo" id="ins_catalogo" class="form-control" required>
        </div>
        <div class="row text-center">
            <div class="col">
                <button type="submit" form="formInstructor" id="btnGuardar" class="btn btn-primary w-100">Guardar</button>
            </div>
            <div class="col">
                <button type="button" id="btnModificar" class="btn btn-warning w-100 mb-2">Modificar</button>
            </div>
            <div class="col">
                <button type="button" id="btnCancelar" class="btn btn-danger w-100 mb-2">Cancelar</button>
            </div>
        </div>
    </form>
</div>
<h1 class="text-center">OFICIALES INSTRUCTORES DE ALTA EN LA ESCUELA REGIONAL DE COMUNICACIONES</h1>
<div class="row mb-4">
    <div class="col table-responsive">
        <table class="table table-bordered table-hover w-100" id="tablaInstructor"></table>
    </div>
</div>
<script src="<?= asset('./build/js/instructor/index.js') ?>"></script>