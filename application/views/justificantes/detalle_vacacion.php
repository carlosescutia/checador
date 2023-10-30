<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>justificantes/guardar/<?= $justificante['cve_justificante'] ?>">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Vacaciones</h1>
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
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="fecha" id="fecha" value="<?=$justificante['fecha'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="detalle" class="col-sm-2 col-form-label">Detalle</label>
                <div class="col-sm-4">
                    <textarea class="form-control" name="detalle" id="detalle" rows="4"><?=$justificante['detalle'] ?></textarea>
                </div>
            </div>
        </div>

        <input type="hidden" name="cve_empleado" id="cve_empleado" value="<?=$justificante['cve_empleado']?>">
        <input type="hidden" name="tipo" id="tipo" value="V">

    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>admin/empleados_detalle/<?=$justificante['cve_empleado']?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
