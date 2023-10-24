                    <div class="card mb-5">
                        <div class="card-header card-sistema text-center">
                            <strong>Justificantes masivos</strong>
                        </div>
                        <div class="card-body">
                            <?php foreach ($justificantes_masivos as $justificantes_masivos_item) { ?>
                            <div class="row alternate-color">
                                <div class="col align-self-center">
                                    <p><a href="<?=base_url()?>justificantes_masivos/detalle/<?=$justificantes_masivos_item['cve_justificante_masivo']?>"><?= $justificantes_masivos_item['fecha'] ?></a></p>
                                </div>
                                <div class="col align-self-center">
                                    <p><?= $justificantes_masivos_item['desc_justificante_masivo'] ?></p>
                                </div>
                                <div class="col align-self-center">
                                    <p><?= $justificantes_masivos_item['tipo'] ?></p>
                                </div>
                                <div class="col-sm-1">
                                    <?php 
                                    $item_eliminar = $justificantes_masivos_item['fecha'] . ' ' . $justificantes_masivos_item['desc_justificante_masivo']; 
                                    $url = base_url() . "justificantes_masivos/eliminar/". $justificantes_masivos_item['cve_justificante_masivo']; 
                                    ?>
                                    <p><a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i>
                                    </a></p>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="card-footer text-end">
                            <form method="post" action="<?= base_url() ?>justificantes_masivos/nuevo">
                                <button type="submit" class="btn btn-primary">Nuevo</button>
                            </form>
                        </div>
                    </div>
