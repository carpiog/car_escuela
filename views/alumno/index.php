<h1 class="text-center">Formulario para ingresar Alumnos</h1>
<div class="row justify-content-center mb-4">
    <div class="col-lg-8">
        <form id="formAlumno" class="border shadow p-4">
            <input type="hidden" name="alu_id" id="alu_id">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="alu_grado_id" class="form-label">Grado</label>
                    <select class="form-select" name="alu_grado_id" id="alu_grado_id" required>
                        <option value="">Seleccione un grado</option>
                        <?php foreach ($grados as $grado): ?>
                            <option value="<?= $grado->gra_id ?>"><?= $grado->gra_nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="alu_rango_id" class="form-label">Rango</label>
                    <select class="form-select" name="alu_rango_id" id="alu_rango_id" required>
                        <option value="">Seleccione un rango</option>
                        <?php foreach ($rangos as $rango): ?>
                            <option value="<?= $rango->ran_id ?>"><?= $rango->ran_nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="alu_primer_nombre" class="form-label">Primer Nombre</label>
                    <input type="text" class="form-control" name="alu_primer_nombre" id="alu_primer_nombre" required>
                </div>
                <div class="col-md-6">
                    <label for="alu_segundo_nombre" class="form-label">Segundo Nombre</label>
                    <input type="text" class="form-control" name="alu_segundo_nombre" id="alu_segundo_nombre">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="alu_primer_apellido" class="form-label">Primer Apellido</label>
                    <input type="text" class="form-control" name="alu_primer_apellido" id="alu_primer_apellido" required>
                </div>
                <div class="col-md-6">
                    <label for="alu_segundo_apellido" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" name="alu_segundo_apellido" id="alu_segundo_apellido">
                </div>
            </div>

            <div class="mb-3">
                <label for="alu_catalogo" class="form-label">Catálogo del Alumno</label>
                <input type="text" class="form-control" name="alu_catalogo" id="alu_catalogo"
                    required pattern="^\d{5}$" minlength="5" maxlength="5"
                    title="El catálogo debe contener exactamente 5 números">
            </div>

            <div class="row text-center">
                <div class="col">
                    <button type="submit" form="formAlumno" id="btnGuardar" class="btn btn-primary w-100">Guardar</button>
                </div>
                <div class="col">
                    <button type="button" id="btnModificar" class="btn btn-warning w-100">Modificar</button>
                </div>
                <div class="col">
                    <button type="button" id="btnCancelar" class="btn btn-danger w-100">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<h2 class="text-center">Listado de Alumnos</h2>
<div class="row mb-4">
    <div class="col table-responsive">
        <table class="table table-bordered table-hover w-100" id="tablaAlumno"></table>
    </div>
</div>

<script src="<?= asset('build/js/alumno/index.js') ?>"></script>