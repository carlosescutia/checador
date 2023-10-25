<main role="main" class="ml-sm-auto px-4">
    <?php $this->session->set_userdata('url_actual', current_url()); ?>
    <?php $url_padre = $this->session->userdata('url_padre') ?>

    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="row">
            <div class="col-md-4">
                <h3><?=$empleado['nom_empleado'] ?></h3>
            </div>
            <div class="col-sm-4 text-left">
                <?php include 'selector_mes.php' ?>
            </div>
        </div>
    </div>

    <div class="card mt-0 mb-3 border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6 offset-sm-1">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p class="fw-bold">Fecha</p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="fw-bold">Entrada</p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="fw-bold">Salida</p>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <p class="fw-bold">Incidente</p>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <p class="fw-bold">Justificante</p>
                        </div>
                    </div>
                    <?php foreach ($incidentes_empleado as $incidentes_empleado_item) { ?>
                    <div class="row alternate-color">
                        <div class="col-sm-2 align-self-center">
                            <p><?= date('d/m/Y', strtotime($incidentes_empleado_item['fecha'])) ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $incidentes_empleado_item['hora_entrada'] ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $incidentes_empleado_item['hora_salida'] ?></p>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <p><a href="<?=base_url()?>justificantes/nuevo_justificante/<?=$incidentes_empleado_item['cve_empleado']?>/<?=$incidentes_empleado_item['fecha']?>"><?= $incidentes_empleado_item['incidente'] ?></a></p>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <?php 
                                $url = '';
                                $texto = '' ;
                                switch ($incidentes_empleado_item['tipo_justificante']) { 
                                    case "E": 
                                        $url = base_url() . "justificantes/detalle_justificante/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = "Entrada justificada" ;
                                        break;
                                    case "S": 
                                        $url = base_url() . "justificantes/detalle_justificante/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = "Salida justificada" ;
                                        break;
                                    case "D": 
                                        $url = base_url() . "justificantes/detalle_justificante/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = "Dia justificado" ;
                                        break;
                                    case "V": 
                                        $url = base_url() . "justificantes/detalle_vacacion/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = "Vacaciones" ;
                                        break;
                                }
                                switch ($incidentes_empleado_item['tipo_justificante_masivo']) { 
                                    case "E": 
                                        $url = base_url() . "justificantes_masivos/detalle/" . $incidentes_empleado_item['cve_justificante_masivo'] ;
                                        $texto = "Entrada masiva justificada" ;
                                        break;
                                    case "S": 
                                        $url = base_url() . "justificantes_masivos/detalle/" . $incidentes_empleado_item['cve_justificante_masivo'] ;
                                        $texto = "Salida masiva justificada" ;
                                        break;
                                    case "D": 
                                        $url = base_url() . "justificantes_masivos/detalle/" . $incidentes_empleado_item['cve_justificante_masivo'] ;
                                        $texto = "Dia masivo justificado" ;
                                        break;
                                }
                            ?>
                            <p><a href="<?=$url?>"><?=$texto?></a></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-4 offset-sm-1">
                    <?php include "vacaciones.php" ?>
                    <?php include "justificantes.php" ?>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=$url_padre ?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
