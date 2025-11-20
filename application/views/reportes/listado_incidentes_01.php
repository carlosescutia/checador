<main role="main" class="ml-sm-auto px-4 mb-3 col-print-12">
    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom d-print-none">
        <div class="col-sm-12">
            <form method="post" action="<?= base_url() ?>reportes/listado_incidentes_01">
                <div class="row">
                    <div class="col-sm-4 text-start">
                        <h1 class="h2">Incidentes por fecha</h1>
                    </div>
                    <div class="col-sm-4 text-start">
                        <div class="row">
                            <div class="col">
                                <input type="date" class="form-control form-control-sm" id="fech_ini" name="fech_ini" value="<?=$fech_ini?>">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control form-control-sm" id="fech_fin" name="fech_fin" value="<?=$fech_fin?>">
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
                <?php foreach ($empleados as $empleados_item): ?>
                <h4 class="bg-secondary text-white py-2"><?= $empleados_item['nom_empleado'] ?> - Incidentes del <?= date('d/M/y', strtotime($fech_ini))?> al <?= date('d/M/y', strtotime($fech_fin))?></h4>
                    <div class="card mt-0 mb-3 border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Entrada</th>
                                                <th scope="col">Salida</th>
                                                <th scope="col">Incidente</th>
                                                <th scope="col">Justificante</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($incidentes_empleados as $incidentes_empleados_item):
                                                if ($incidentes_empleados_item['cve_empleado'] !== $empleados_item['cve_empleado']):
                                                    continue;
                                                endif; 
                                            ?>
                                                <?php if ( $incidentes_empleados_item['cve_incidente'] and ! $incidentes_empleados_item['cve_justificante'] ): ?>
                                                    <tr>
                                                        <td>
                                                            <?= get_nom_dia($incidentes_empleados_item['fecha']) ?>&nbsp;<?= date('d/m/Y', strtotime($incidentes_empleados_item['fecha'])) ?>
                                                        </td>
                                                        <td>
                                                            <?php if ( ! in_array($incidentes_empleados_item['cve_incidente'], array('6', '7')) ): ?>
                                                                <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','2','3','4','9')) ): ?> <u> <?php endif ?>
                                                                <?= is_null($incidentes_empleados_item['hora_entrada']) ? '' : date('H:i', strtotime($incidentes_empleados_item['hora_entrada'])) ?>
                                                                <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','2','3','4','9')) ): ?> </u> <?php endif ?>
                                                            <?php endif ?>
                                                        </td>
                                                        <td>
                                                            <?php if ( ! in_array($incidentes_empleados_item['cve_incidente'], array('8', '9', '10')) ): ?>
                                                                <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','3','5','6')) ): ?> <u> <?php endif ?>
                                                                <?= is_null($incidentes_empleados_item['hora_salida']) ? '' : date('H:i', strtotime($incidentes_empleados_item['hora_salida'])) ?>
                                                                <?php if ( in_array($incidentes_empleados_item['cve_incidente'], array('1','3','5','6')) ): ?> </u> <?php endif ?>
                                                            <?php endif ?>
                                                        </td>
                                                        <td>
                                                            <?php if ( ! $incidentes_empleados_item['cve_justificante'] ): ?>
                                                                <?= $incidentes_empleados_item['desc_incidente'] ?>
                                                            <?php endif ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                                $texto = '' ;
                                                                if ($incidentes_empleados_item['tipo_justificante']): 
                                                                    $texto = $incidentes_empleados_item['desc_corta_justificante'] . ': '. $incidentes_empleados_item['desc_justificante'];
                                                                endif
                                                            ?>
                                                            <?=$texto?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="page-break" />
                <?php endforeach ?>
            </div>
        </div>
    </div>

</main>
