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
