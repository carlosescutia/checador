<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>eventualidades/guardar/<?= $eventualidad['cve_eventualidad'] ?>">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Editar tipo de justificante</h1>
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
                <label for="nom_eventualidad" class="col-sm-2 col-form-label">Nombre tipo justificante</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="nom_eventualidad" id="nom_eventualidad" value="<?=$eventualidad['nom_eventualidad'] ?>">
                </div>
            </div>
        </div>

    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>eventualidades" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
