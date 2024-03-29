<main role="main" class="ml-sm-auto px-4">
    <?php $url_padre = $this->session->userdata('url_padre'); ?>

    <form method="post" action="<?= base_url() ?>justificantes_masivos/guardar">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Nuevo justificante masivo</h1>
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
                <div class="col-sm-2">
                    <input type="date" class="form-control" name="fecha" id="fecha">
                </div>
            </div>
            <div class="form-group row">
                <label for="desc_justificante_masivo" class="col-sm-2 col-form-label">Descripción</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="desc_justificante_masivo" id="desc_justificante_masivo">
                </div>
            </div>
            <div class="form-group row">
                <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                <div class="col-sm-2">
                    <select class="form-select" name="tipo" id="tipo">
                        <option value="D" >Día</option>
                        <option value="E" >Entrada</option>
                        <option value="S" >Salida</option>
                    </select>
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
