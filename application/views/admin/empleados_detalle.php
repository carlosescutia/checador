<main role="main" class="ml-sm-auto px-4">
    <?php $this->session->set_userdata('url_actual', current_url()); ?>
    <?php $url_padre = $this->session->userdata('url_padre') ?>

    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="row">
            <div class="col-md-4">
                <h3 class="d-print-none"><?=$empleado['nom_empleado'] ?></h3>
            </div>
            <div class="col-sm-4 text-left">
                <?php include 'selector_mes.php' ?>
            </div>
            <div class="col-sm-4 text-end">
                <a href="javascript:window.print()" class="btn btn-primary d-print-none">Generar pdf</a>
            </div>
            <h4 class="d-none d-print-block bg-secondary text-white py-2"><?= $empleado['nom_empleado'] ?> - Asistencia de <?=get_nombre_mes($mes)?> <?= $anio ?></h4>
        </div>
    </div>

    <div class="card mt-0 mb-3 border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col-2">Fecha</th>
                                <th scope="col-1">Entrada</th>
                                <th scope="col-1">Salida</th>
                                <th scope="col-2">Incidente</th>
                                <th scope="col-6">Justificante</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($incidentes_empleado as $incidentes_empleado_item): ?>
                            <tr>
                                <td class="col-2">
                                    <?= get_nom_dia($incidentes_empleado_item['fecha']) ?>&nbsp;<?= date('d/m/y', strtotime($incidentes_empleado_item['fecha'])) ?>
                                </td>
                                <td class="col-1">
                                    <?php if ( ! in_array($incidentes_empleado_item['cve_incidente'], array('6', '7')) ): ?>
                                        <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','2','3','4','9')) ): ?> <u> <?php endif ?>
                                        <?= is_null($incidentes_empleado_item['hora_entrada']) ? '' : date('H:i', strtotime($incidentes_empleado_item['hora_entrada'])) ?>
                                        <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','2','3','4','9')) ): ?> </u> <?php endif ?>
                                    <?php endif ?>
                                </td>
                                <td class="col-1">
                                    <?php if ( ! in_array($incidentes_empleado_item['cve_incidente'], array('8', '9', '10')) ): ?>
                                        <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','3','5','6')) ): ?> <u> <?php endif ?>
                                        <?= is_null($incidentes_empleado_item['hora_salida']) ? '' : date('H:i', strtotime($incidentes_empleado_item['hora_salida'])) ?>
                                        <?php if ( in_array($incidentes_empleado_item['cve_incidente'], array('1','3','5','6')) ): ?> </u> <?php endif ?>
                                    <?php endif ?>
                                </td>
                                <td class="col-2">
                                    <?php if ( ! $incidentes_empleado_item['cve_justificante'] ): ?>
                                        <?php if (in_array('99', $accesos_sistema_rol)): ?>
                                            <a href="<?=base_url()?>justificantes/nuevo_justificante/<?=$incidentes_empleado_item['cve_empleado']?>/<?=$incidentes_empleado_item['fecha']?>"><?= $incidentes_empleado_item['desc_incidente'] ?></a>
                                        <?php else: ?>
                                            <?= $incidentes_empleado_item['desc_incidente'] ?>
                                        <?php endif ?>
                                    <?php endif ?>
                                </td>
                               <?php 
                                    $url = '';
                                    $texto = '' ;
                                    switch ($incidentes_empleado_item['tipo_justificante']): 
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
                                            $texto = $incidentes_empleado_item['desc_corta_justificante'] . ': '. $incidentes_empleado_item['desc_justificante'];
                                            break;
                                        case "hc": 
                                            $url = '#';
                                            $texto = $incidentes_empleado_item['desc_corta_justificante'] . ': '. $incidentes_empleado_item['desc_justificante'];
                                            break;
                                    endswitch
                                ?>
                                <td class="col-6">
                                    <?php if ($incidentes_empleado_item['tipo_justificante'] == 'hc'): ?>
                                        <?=$texto?>
                                    <?php else:?>
                                        <a href="<?=$url?>"><?=$texto?></a>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4">
                    <?php include "horas.php" ?>
                    <?php include "vacaciones.php" ?>
                    <?php include "justificantes.php" ?>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=$url_padre ?>" class="btn btn-secondary d-print-none">Volver</a>
        </div>
    </div>

</main>
