<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>parametros_sistema/guardar/<?= $parametros_sistema['cve_parametro_sistema'] ?>">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Editar parámetro del sistema</h1>
                </div>
                <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group row">
                <label for="cve_parametro_sistema" class="col-sm-2 col-form-label">Clave</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="cve_parametro_sistema" id="cve_parametro_sistema" value="<?=$parametros_sistema['cve_parametro_sistema'] ?>" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="nom_parametro_sistema" class="col-sm-2 col-form-label">Parámetro</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="nom_parametro_sistema" id="nom_parametro_sistema" value="<?=$parametros_sistema['nom_parametro_sistema'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="valor_parametro_sistema" class="col-sm-2 col-form-label">Valor</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="valor_parametro_sistema" id="valor_parametro_sistema" value="<?=$parametros_sistema['valor_parametro_sistema'] ?>">
                </div>
            </div>
        </div>

    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>parametros_sistema" class="btn btn-secondary boton">Volver</a>
        </div>
    </div>

</main>
