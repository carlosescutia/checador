<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>justificantes/guardar">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Nueva vacación</h1>
                </div>
                <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="fecha" class="col-sm-3 col-form-label">Fecha inicial</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="fecha" id="fecha">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fech_fin" class="col-sm-3 col-form-label">Fecha final</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="fech_fin" id="fech_fin">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="detalle" class="col-sm-3 col-form-label">Detalle</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="detalle" id="detalle" rows="4"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="cve_empleado" id="cve_empleado" value="<?=$cve_empleado?>">
        <input type="hidden" name="tipo" id="tipo" value="V">

    </form>


    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>admin/empleados_detalle/<?=$cve_empleado?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
