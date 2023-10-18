<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <div class="row">
                <div class="col-sm-10 text-left">
                    <h1 class="h2">Empleados</h1>
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
                            <p class="small"><strong>Clave</strong></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p class="small"><strong>Nombre</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Horario</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($empleados as $empleados_item) { ?>
                <div class="col-sm-7 alternate-color">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p><?= $empleados_item['cod_empleado'] ?></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p><a href="<?=base_url()?>incidentes_empleados/detalle/<?=$empleados_item['cve_empleado']?>"><?= $empleados_item['nom_empleado'] ?></a></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $empleados_item['desc_horario'] ?></p>
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
            <a href="#" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
