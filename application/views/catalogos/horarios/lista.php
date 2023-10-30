<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <div class="row">
                <div class="col-sm-10 text-left">
                    <h1 class="h2">Horarios</h1>
                </div>
                <div class="col-sm-2 text-right">
                    <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                    <form method="post" action="<?= base_url() ?>horarios/nuevo">
                        <button type="submit" class="btn btn-primary">Nuevo</button>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div style="min-height: 46vh">
            <div class="row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-4 align-self-center">
                            <p class="small"><strong>Descripción</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Hora de entrada</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Hora de salida</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($horarios as $horarios_item) { ?>
                <div class="col-sm-7 alternate-color">
                    <div class="row">
                        <div class="col-sm-4 align-self-center">
                            <p><a href="<?=base_url()?>horarios/detalle/<?=$horarios_item['cve_horario']?>"><?= $horarios_item['desc_horario'] ?></a></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $horarios_item['hora_entrada'] ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $horarios_item['hora_salida'] ?></p>
                        </div>
                        <div class="col-sm-1">
                            <?php 
                            $item_eliminar = $horarios_item['cve_horario'] . ' '. $horarios_item['desc_horario']; 
                            $url = base_url() . "horarios/eliminar/". $horarios_item['cve_horario']; 
                            ?>
                            <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                            <p><a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i>
                            <?php } ?>
                            </a></p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>catalogos" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
