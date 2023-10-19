<main role="main" class="ml-sm-auto px-4">

    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="row">
            <div class="col-md-4">
                <h3>Incidentes de <?=$empleado['nom_empleado'] ?></h3>
            </div>
            <div class="col-sm-4 text-left">
                <?php include 'selector_mes.php' ?>
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
            <?php foreach ($incidentes_empleado as $incidentes_empleado_item) { ?>
            <div class="col-sm-6 alternate-color">
                <div class="row">
                    <div class="col-sm-3 align-self-center">
                        <p><?= date('d/m/Y', strtotime($incidentes_empleado_item['fecha'])) ?></p>
                    </div>
                    <div class="col-sm-2 align-self-center">
                        <p><?= $incidentes_empleado_item['hora_entrada'] ?></p>
                    </div>
                    <div class="col-sm-2 align-self-center">
                        <p><?= $incidentes_empleado_item['hora_salida'] ?></p>
                    </div>
                    <div class="col-sm-4 align-self-center">
                        <p><?= $incidentes_empleado_item['incidente'] ?></p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="#" onclick="history.go(-1);event.preventDefault();" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
