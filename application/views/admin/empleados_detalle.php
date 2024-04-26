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
                <div class="col-sm-7">
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
                            <p><span><?= get_nom_dia($incidentes_empleado_item['fecha']) ?></span>&nbsp;<?= date('d/m/y', strtotime($incidentes_empleado_item['fecha'])) ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <?php if ( ! in_array($incidentes_empleado_item['cve_incidente'], array('6', '7')) ) { ?>
                                <p>
                                    <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','2','3','4','9')) ) { ?>
                                        <u>
                                    <?php } ?>
                                        <?= $incidentes_empleado_item['hora_entrada'] ?>
                                    <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','2','3','4','9')) ) { ?>
                                        </u>
                                    <?php } ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <?php if ( ! in_array($incidentes_empleado_item['cve_incidente'], array('8', '9', '10')) ) { ?>
                                <p>
                                    <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','3','5','6')) ) { ?>
                                        <u>
                                    <?php } ?>
                                        <?= $incidentes_empleado_item['hora_salida'] ?>
                                    <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','3','5','6')) ) { ?>
                                        </u>
                                    <?php } ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <?php if ( ! $incidentes_empleado_item['cve_justificante'] ) { ?>
                                <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                                    <p><a href="<?=base_url()?>justificantes/nuevo_justificante/<?=$incidentes_empleado_item['cve_empleado']?>/<?=$incidentes_empleado_item['fecha']?>"><?= $incidentes_empleado_item['desc_incidente'] ?></a></p>
                                <?php } else { ?>
                                    <p><?= $incidentes_empleado_item['desc_incidente'] ?></p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="col-sm-3 align-self-center">
                            <?php 
                                $url = '';
                                $texto = '' ;
                                switch ($incidentes_empleado_item['tipo_justificante']) { 
                                    case "di": 
                                        $url = base_url() . "dias_inhabiles/detalle/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = $incidentes_empleado_item['desc_corta_justificante'] . ': '. $incidentes_empleado_item['desc_justificante'];
                                        break;
                                    case "jm": 
                                        $url = base_url() . "justificantes_masivos/detalle/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = $incidentes_empleado_item['desc_corta_justificante'] . ': '. $incidentes_empleado_item['desc_justificante'];
                                        break;
                                    case "ji": 
                                        $url = base_url() . "justificantes/detalle_justificante/" . $incidentes_empleado_item['cve_justificante'] ;
                                        $texto = $incidentes_empleado_item['desc_corta_justificante'];
                                        break;
                                    case "hc": 
                                        $url = '#';
                                        $texto = $incidentes_empleado_item['desc_corta_justificante'] . ': '. $incidentes_empleado_item['desc_justificante'];
                                        break;
                                }
                            ?>
                            <?php if ($incidentes_empleado_item['tipo_justificante'] == 'hc') { ?>
                                <p><?=$texto?></a></p>
                            <?php } else { ?>
                                <p><a href="<?=$url?>"><?=$texto?></a></p>
                            <?php } ?>
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
