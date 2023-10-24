<main role="main" class="ml-sm-auto px-4 mb-3">
    <?php $this->session->set_userdata('url_actual', current_url()); ?>
    <?php $this->session->set_userdata('url_padre', current_url()); ?>
    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-4">
                    <h2>Días con incidentes</h2>
                </div>
                <div class="col-sm-4 text-left">
                    <?php include 'selector_mes.php' ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-0 mb-3 border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 offset-sm-1">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Fecha</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small text-center"><strong>Incidentes</strong></p>
                        </div>
                    </div>
                    <?php foreach ($incidentes_fechas as $incidentes_fechas_item) { ?>
                    <div class="row alternate-color">
                        <div class="col-sm-2 align-self-center">
                            <p><a href="<?=base_url()?>admin/fechas_detalle/<?= $incidentes_fechas_item['fecha'] ?>"><?= $incidentes_fechas_item['fecha'] ?></a></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="text-center"><?= $incidentes_fechas_item['num_incidentes'] ?></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-4 offset-sm-1">
                    <?php include "dias_inhabiles.php" ?>
                    <?php include "justificantes_masivos.php" ?>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>admin" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
