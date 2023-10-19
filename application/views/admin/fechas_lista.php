<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <div class="row">
                <div class="col-sm-10 text-left">
                    <h1 class="h2">Dias</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div style="min-height: 46vh">
            <div class="row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Fecha</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small text-center"><strong>Incidentes</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($incidentes_fechas as $incidentes_fechas_item) { ?>
                <div class="col-sm-7 alternate-color">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p><a href="<?=base_url()?>admin/fechas_detalle/<?= $incidentes_fechas_item['fecha'] ?>"><?= $incidentes_fechas_item['fecha'] ?></a></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="text-center"><?= $incidentes_fechas_item['num_incidentes'] ?></p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="#" onclick="history.go(-1);event.preventDefault();" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
