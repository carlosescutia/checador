<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <div class="row">
                <div class="col-sm-10 text-start">
                    <h1 class="h2">Parámetros del sistema</h1>
                </div>
                <div class="col-sm-2 text-end">
                    <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                    <form method="post" action="<?= base_url() ?>parametros_sistema/nuevo">
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
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Clave</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Parámetro</strong></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p class="small"><strong>Valor</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($parametros_sistema as $parametros_sistema_item) { ?>
                <div class="col-sm-10 alternate-color">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p><?= $parametros_sistema_item['cve_parametro_sistema'] ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><a href="<?=base_url()?>parametros_sistema/detalle/<?=$parametros_sistema_item['cve_parametro_sistema']?>"><?= $parametros_sistema_item['nom_parametro_sistema'] ?></a></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p><?= $parametros_sistema_item['valor_parametro_sistema'] ?></a></p>
                        </div>
                        <div class="col-sm-1">
                            <?php 
                            $item_eliminar = $parametros_sistema_item['nom_parametro_sistema']; 
                            $url = base_url() . "parametros_sistema/eliminar/". $parametros_sistema_item['cve_parametro_sistema']; 
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
            <a href="<?=base_url()?>catalogos" class="btn btn-secondary boton">Volver</a>
        </div>
    </div>

</main>
