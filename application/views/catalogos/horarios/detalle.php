<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>horarios/guardar/<?= $horario['cve_horario'] ?>">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Editar horario</h1>
                </div>
                <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group row">
                <label for="desc_horario" class="col-sm-2 col-form-label">Descripción</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="desc_horario" id="desc_horario" value="<?=$horario['desc_horario'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="hora_entrada" class="col-sm-2 col-form-label">Hora de entrada</label>
                <div class="col-sm-2">
                    <input type="time" class="form-control" name="hora_entrada" id="hora_entrada" value="<?=$horario['hora_entrada'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="hora_salida" class="col-sm-2 col-form-label">Hora de salida</label>
                <div class="col-sm-2">
                    <input type="time" class="form-control" name="hora_salida" id="hora_salida" value="<?=$horario['hora_salida'] ?>">
                </div>
            </div>
        </div>

    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>horarios" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
