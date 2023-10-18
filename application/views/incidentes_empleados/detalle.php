<main role="main" class="ml-sm-auto px-4">

    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="row">
            <div class="col-md-10">
                <h1>Incidentes de <?=$empleado['nom_empleado'] ?></h1>
            </div>
        </div>
    </div>

    <div class="card mt-0 mb-3 border-0">
        <div class="card-body">
            <div class="col-sm-6 alternate-color">
                <div class="row">
                    <div class="col-sm-3 align-self-center">
                        <p class="fw-bold">Fecha</p>
                    </div>
                    <div class="col-sm-2 align-self-center">
                        <p class="fw-bold">Entrada</p>
                    </div>
                    <div class="col-sm-2 align-self-center">
                        <p class="fw-bold">Salida</p>
                    </div>
                    <div class="col-sm-4 align-self-center">
                        <p class="fw-bold">Incidente</p>
                    </div>
                </div>
            </div>
            <?php foreach ($incidentes as $incidentes_item) { ?>
            <div class="col-sm-6 alternate-color">
                <div class="row">
                    <div class="col-sm-3 align-self-center">
                        <p><?= date('d/m/Y', strtotime($incidentes_item['fecha'])) ?></p>
                    </div>
                    <div class="col-sm-2 align-self-center">
                        <p><?= $incidentes_item['hora_entrada'] ?></p>
                    </div>
                    <div class="col-sm-2 align-self-center">
                        <p><?= $incidentes_item['hora_salida'] ?></p>
                    </div>
                    <div class="col-sm-4 align-self-center">
                        <p><?= $incidentes_item['incidente'] ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>incidentes_empleados" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
