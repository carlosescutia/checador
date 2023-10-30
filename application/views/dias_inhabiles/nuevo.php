<main role="main" class="ml-sm-auto px-4">
    <?php $url_padre = $this->session->userdata('url_padre'); ?>

    <form method="post" action="<?= base_url() ?>dias_inhabiles/guardar">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Nuevo día inhábil</h1>
                </div>
                <?php if (in_array('99', $accesos_sistema_rol)) { ?> <div class="col-md-2 text-right"> <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group row">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-2">
                    <input type="date" class="form-control" name="fecha" id="fecha">
                </div>
            </div>
            <div class="form-group row">
                <label for="desc_dia_inhabil" class="col-sm-2 col-form-label">Descripción</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="desc_dia_inhabil" id="desc_dia_inhabil">
                </div>
            </div>
        </div>

    </form>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=$url_padre ?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
