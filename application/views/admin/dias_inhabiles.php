                    <div class="card mb-5">
                        <div class="card-header card-sistema text-center">
                            <strong>Dias inhábiles</strong>
                        </div>
                        <div class="card-body">
                            <?php foreach ($dias_inhabiles as $dias_inhabiles_item) { ?>
                            <div class="row alternate-color">
                                <div class="col align-self-center">
                                    <p><a href="<?=base_url()?>dias_inhabiles/detalle/<?=$dias_inhabiles_item['cve_dia_inhabil']?>"><?= $dias_inhabiles_item['fecha'] ?></a></p>
                                </div>
                                <div class="col align-self-center">
                                    <p><?= $dias_inhabiles_item['desc_dia_inhabil'] ?></p>
                                </div>
                                <div class="col-sm-1">
                                    <?php 
                                    $item_eliminar = $dias_inhabiles_item['fecha'] . ' ' . $dias_inhabiles_item['desc_dia_inhabil']; 
                                    $url = base_url() . "dias_inhabiles/eliminar/". $dias_inhabiles_item['cve_dia_inhabil']; 
                                    ?>
                                    <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                                    <p><a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i></a></p>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                        <div class="card-footer text-end">
                            <form method="post" action="<?= base_url() ?>dias_inhabiles/nuevo">
                                <button type="submit" class="btn btn-primary">Nuevo</button>
                            </form>
                        </div>
                        <?php } ?>
                    </div>
