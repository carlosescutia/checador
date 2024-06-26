<main role="main" class="ml-sm-auto px-4 mb-3 col-print-12">
    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom d-print-none">
        <div class="col-sm-12">
            <form method="post" action="<?= base_url() ?>reportes/listado_asistencia_01">
                <div class="row">
                    <div class="col-sm-4 text-start">
                        <h1 class="h2">Asistencia por mes</h1>
                    </div>
                    <div class="col-sm-4 text-start">
                        <div class="row">
                            <div class="col">
                                <select class="form-select form-select-sm" id="mes" name="mes">
                                    <option value="1" <?= $mes == '1' ? 'selected' : '' ?>>Enero</option>
                                    <option value="2" <?= $mes == '2' ? 'selected' : '' ?>>Febrero</option>
                                    <option value="3" <?= $mes == '3' ? 'selected' : '' ?>>Marzo</option>
                                    <option value="4" <?= $mes == '4' ? 'selected' : '' ?>>Abril</option>
                                    <option value="5" <?= $mes == '5' ? 'selected' : '' ?>>Mayo</option>
                                    <option value="6" <?= $mes == '6' ? 'selected' : '' ?>>Junio</option>
                                    <option value="7" <?= $mes == '7' ? 'selected' : '' ?>>Julio</option>
                                    <option value="8" <?= $mes == '8' ? 'selected' : '' ?>>Agosto</option>
                                    <option value="9" <?= $mes == '9' ? 'selected' : '' ?>>Septiembre</option>
                                    <option value="10" <?= $mes == '10' ? 'selected' : '' ?>>Octubre</option>
                                    <option value="11" <?= $mes == '11' ? 'selected' : '' ?>>Noviembre</option>
                                    <option value="12" <?= $mes == '12' ? 'selected' : '' ?>>Diciembre</option>
                                </select>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control form-control-sm" id="anio" name="anio" min="2022" max="2030" value="<?=$anio?>">
                            </div>
                            <div class="col">
                                <button class="btn btn-success btn-sm d-print-none">Cambiar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 text-end">
                        <a href="javascript:window.print()" class="btn btn-primary d-print-none">Generar pdf</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div style="min-height: 46vh">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($empleados as $empleados_item) { ?>
                <h4 class="bg-secondary text-white py-2"><?= $empleados_item['nom_empleado'] ?> - Asistencia de <?=get_nombre_mes($mes)?> <?= $anio ?></h4>
                    <div class="card mt-0 mb-3 border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
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
                                    <?php foreach ($incidentes_empleados as $incidentes_empleados_item) { 
                                        if ($incidentes_empleados_item['cve_empleado'] !== $empleados_item['cve_empleado']) {
                                            continue;
                                        }
                                    ?>
                                    <div class="row alternate-color">
                                        <div class="col-sm-2 align-self-center">
                                            <p><span><?= get_nom_dia($incidentes_empleados_item['fecha']) ?></span>&nbsp;<?= date('d/m/Y', strtotime($incidentes_empleados_item['fecha'])) ?></p>
                                        </div>
                                        <div class="col-sm-2 align-self-center">
                                            <?php if ( ! in_array($incidentes_empleados_item['cve_incidente'], array('6', '7')) ) { ?>
                                                <p>
                                                    <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','2','3','4','9')) ) { ?>
                                                        <u>
                                                    <?php } ?>
                                                        <?= $incidentes_empleados_item['hora_entrada'] ?>
                                                    <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','2','3','4','9')) ) { ?>
                                                        </u>
                                                    <?php } ?>
                                                </p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-2 align-self-center">
                                            <?php if ( ! in_array($incidentes_empleados_item['cve_incidente'], array('8', '9', '10')) ) { ?>
                                                <p>
                                                    <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','3','5','6')) ) { ?>
                                                        <u>
                                                    <?php } ?>
                                                        <?= $incidentes_empleados_item['hora_salida'] ?>
                                                    <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','3','5','6')) ) { ?>
                                                        </u>
                                                    <?php } ?>
                                                </p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-3 align-self-center">
                                            <?php if ( ! $incidentes_empleados_item['cve_justificante'] ) { ?>
                                                <p><?= $incidentes_empleados_item['desc_incidente'] ?></p>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-3 align-self-center">
                                            <?php 
                                                $texto = '' ;
                                                switch ($incidentes_empleados_item['tipo_justificante']) { 
                                                    case "di": 
                                                        $texto = $incidentes_empleados_item['desc_corta_justificante'] . ': '. $incidentes_empleados_item['desc_justificante'];
                                                        break;
                                                    case "jm": 
                                                        $texto = $incidentes_empleados_item['desc_corta_justificante'] . ': '. $incidentes_empleados_item['desc_justificante'];
                                                        break;
                                                    case "ji": 
                                                        $texto = $incidentes_empleados_item['desc_corta_justificante'];
                                                        break;
                                                    case "hc": 
                                                        $texto = $incidentes_empleados_item['desc_corta_justificante'] . ': '. $incidentes_empleados_item['desc_justificante'];
                                                        break;
                                                }
                                            ?>
                                            <p><?=$texto?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="page-break" />
                <?php } ?>
            </div>
        </div>
    </div>

</main>
