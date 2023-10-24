<main role="main" class="ml-sm-auto px-4 mb-3">
    <?php $this->session->set_userdata('url_actual', current_url()); ?>
    <?php $this->session->set_userdata('url_padre', current_url()); ?>
    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-8 text-left">
                    <h2><?= $titulo ?></h2>
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
                            <p class="small"><strong>Clave</strong></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p class="small"><strong>Nombre</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Horario</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small text-center"><strong>Incidentes</strong></p>
                        </div>
                    </div>
                    <?php foreach ($incidentes_empleados as $incidentes_empleados_item) { ?>
                    <div class="row alternate-color">
                        <div class="col-sm-2 align-self-center">
                            <p><?= $incidentes_empleados_item['cod_empleado'] ?></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p><a href="<?=base_url()?>admin/empleados_detalle/<?=$incidentes_empleados_item['cve_empleado']?>"><?= $incidentes_empleados_item['nom_empleado'] ?></a></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $incidentes_empleados_item['desc_horario'] ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="text-center"><?= $incidentes_empleados_item['num_incidentes'] ?></p>
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
            <a href="<?=base_url()?>admin/fechas_incidentes" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
