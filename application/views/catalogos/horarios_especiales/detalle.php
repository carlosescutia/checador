<main role="main" class="ml-sm-auto px-4">
    
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="<?= base_url() ?>horarios_especiales/guardar">

                    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
                        <div class="row">
                            <div class="col-md-10">
                                <h1 class="h2">Editar horario especial</h1>
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
                            <label for="cve_empleado" class="col-sm-2 col-form-label">Empleado</label>
                            <div class="col-sm-4">
                                <select class="form-select" name="cve_empleado" id="cve_empleado">
                                    <?php foreach ($empleados as $empleados_item) { ?>
                                        <option value="<?= $empleados_item['cve_empleado'] ?>" <?= $horario_especial['cve_empleado'] == $empleados_item['cve_empleado'] ? 'selected' : '' ?> ><?= $empleados_item['nom_empleado'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nom_horario_especial" class="col-sm-2 col-form-label">Nombre del horario</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="nom_horario_especial" id="nom_horario_especial" value="<?=$horario_especial['nom_horario_especial'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fech_ini" class="col-sm-2 col-form-label">Inicio</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="fech_ini" id="fech_ini" value="<?=$horario_especial['fech_ini'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fech_fin" class="col-sm-2 col-form-label">Fin</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" name="fech_fin" id="fech_fin" value="<?=$horario_especial['fech_fin'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="horario_base" class="col-sm-2 col-form-label">Horario base</label>
                            <div class="col-sm-2">
                                <label class="col-form-label"><?= $horario_especial['desc_horario'] ?></label>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="id_horario_especial" id="id_horario_especial" value="<?= $horario_especial['id_horario_especial'] ?>">
                </form>
            </div>

            <div class="col-md-5 offset-md-1">
                <?php include "horarios_especiales_dias.php" ?>
            </div>

        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>horarios_especiales" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
