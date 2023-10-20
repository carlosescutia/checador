<main role="main" class="ml-sm-auto px-4">

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
                <div class="col-sm-6">
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
                    <?php foreach ($incidentes_empleado as $incidentes_empleado_item) { ?>
                    <div class="row alternate-color">
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
                            <p><a href="<?=base_url()?>justificantes/nuevo_justificante/<?=$incidentes_empleado_item['cve_empleado']?>/<?=$incidentes_empleado_item['fecha']?>"><?= $incidentes_empleado_item['incidente'] ?></a></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-sm-4 offset-sm-1 offset-sm-1 offset-sm-1">
                    <div class="card mb-5">
                        <div class="card-header card-sistema text-center">
                            <strong>Vacaciones</strong>
                        </div>
                        <div class="card-body">
                            <?php foreach ($vacaciones_empleado as $vacaciones_empleado_item) { ?>
                            <div class="row alternate-color">
                                <div class="col">
                                    <p><a href="<?=base_url()?>justificantes/detalle_vacacion/<?=$vacaciones_empleado_item['cve_justificante']?>"><?= date('d/m/Y', strtotime($vacaciones_empleado_item['fecha'])) ?></a></p>
                                </div>
                                <div class="col">
                                    <p><?= $vacaciones_empleado_item['detalle'] ?></p>
                                </div>
                                <div class="col-sm-1">
                                    <?php 
                                    $item_eliminar = date('d/m/Y', strtotime($vacaciones_empleado_item['fecha'])) . ' - ' . $vacaciones_empleado_item['tipo'];
                                    $url = base_url() . "justificantes/eliminar/". $vacaciones_empleado_item['cve_justificante']; 
                                    ?>
                                    <p><a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i>
                                    </a></p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="card-footer text-end">
                            <a class="btn btn-primary btn-sm" href="<?=base_url()?>justificantes/nueva_vacacion/<?=$incidentes_empleado_item['cve_empleado']?>">Agregar</a>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header card-sistema text-center">
                            <strong>Justificantes</strong>
                        </div>
                        <div class="card-body">
                            <?php foreach ($justificantes_empleado as $justificantes_empleado_item) { ?>
                            <div class="row alternate-color">
                                <div class="col">
                                    <p><a href="<?=base_url()?>justificantes/detalle_justificante/<?=$justificantes_empleado_item['cve_justificante']?>"><?= date('d/m/Y', strtotime($justificantes_empleado_item['fecha'])) ?> - <?= $justificantes_empleado_item['tipo'] ?></a></p>
                                </div>
                                <div class="col">
                                    <p><?= $justificantes_empleado_item['detalle'] ?></p>
                                </div>
                                <div class="col-sm-1">
                                    <?php 
                                    $item_eliminar = date('d/m/Y', strtotime($justificantes_empleado_item['fecha'])) . ' - ' . $justificantes_empleado_item['tipo'];
                                    $url = base_url() . "justificantes/eliminar/". $justificantes_empleado_item['cve_justificante']; 
                                    ?>
                                    <p><a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i>
                                    </a></p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="#" onclick="history.go(-1);event.preventDefault();" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
