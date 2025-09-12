<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>periodos/guardar/<?= $periodo['id_periodo'] ?>">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Editar periodo</h1>
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
                <label for="nom_periodo" class="col-sm-2 col-form-label">Nombre periodo</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="nom_periodo" id="nom_periodo" value="<?=$periodo['nom_periodo'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="orden" class="col-sm-2 col-form-label">Orden</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="orden" id="orden" value="<?=$periodo['orden'] ?>">
                </div>
            </div>
        </div>

    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>periodos" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
