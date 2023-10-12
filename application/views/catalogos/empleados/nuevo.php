<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>empleados/guardar">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Nuevo empleado</h1>
                </div>
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group row">
                <label for="cod_empleado" class="col-sm-2 col-form-label">Clave</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="cod_empleado" id="cod_empleado">
                </div>
            </div>
            <div class="form-group row">
                <label for="nom_empleado" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nom_empleado" id="nom_empleado">
                </div>
            </div>
            <div class="form-group row">
                <label for="activo" class="col-sm-2 col-form-label">Activo</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="activo" id="activo">
                </div>
            </div>
            <div class="form-group row">
                <label for="cve_horario" class="col-sm-2 col-form-label">Horario</label>
                <div class="col-sm-3">
                    <select class="form-select" name="cve_horario" id="cve_horario">
                        <?php foreach ($horarios as $horarios_item) { ?>
                        <option value="<?= $horarios_item['cve_horario'] ?>" ><?= $horarios_item['desc_horario'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

    </form>


    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>empleados" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
