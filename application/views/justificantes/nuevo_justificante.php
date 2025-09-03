<main role="main" class="ml-sm-auto px-4">
    <form method="post" action="<?= base_url() ?>justificantes/guardar">
        <div class="col-sm-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-sm-10">
                    <h1 class="h2">Nuevo justificante</h1>
                </div>
                <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                <div class="col-sm-2 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="fecha" class="col-sm-3 col-form-label">Fecha inicial</label>
                        <div class="col-sm-4">
                        <input type="date" class="form-control <?= empty($fecha) ? '' : 'bg-dark-subtle'?>" name="fecha" id="fecha" value="<?= $fecha ?>" <?= empty($fecha) ? '' : 'readonly'?> >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fech_fin" class="col-sm-3 col-form-label">Fecha final</label>
                        <div class="col-sm-4">
                        <input type="date" class="form-control <?= empty($fecha) ? '' : 'bg-dark-subtle'?>" name="fech_fin" id="fech_fin" value="<?= $fecha ?>" <?= empty($fecha) ? '' : 'readonly'?> >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipo" class="col-sm-3 col-form-label">Tipo</label>
                        <div class="col-sm-4">
                            <select class="form-select" name="tipo" id="tipo">
                                <option value="D" >Día</option>
                                <option value="E" >Entrada</option>
                                <option value="S" >Salida</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cve_eventualidad" class="col-sm-3 col-form-label">Eventualidad</label>
                        <div class="col-sm-6">
                            <select class="form-select" name="cve_eventualidad" id="cve_eventualidad">
                                <option value="0" ></option>
                                <?php foreach ($eventualidades as $eventualidades_item) { ?>
                                <option value="<?= $eventualidades_item['cve_eventualidad'] ?>" ><?= $eventualidades_item['nom_eventualidad'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="detalle" class="col-sm-3 col-form-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="detalle" id="detalle" rows="6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="cve_empleado" id="cve_empleado" value="<?=$cve_empleado?>">
    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>admin/empleados_detalle/<?=$cve_empleado?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
