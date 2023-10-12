<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <div class="row">
                <div class="col-sm-10 text-left">
                    <h1 class="h2">Justificantes masivos</h1>
                </div>
                <div class="col-sm-2 text-right">
                    <form method="post" action="<?= base_url() ?>justificantes_masivos/nuevo">
                        <button type="submit" class="btn btn-primary">Nuevo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div style="min-height: 46vh">
            <div class="row">
                <div class="col-sm-7">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Fecha</strong></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p class="small"><strong>Descripción</strong></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p class="small"><strong>Tipo</strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($justificantes_masivos as $justificantes_masivos_item) { ?>
                <div class="col-sm-7 alternate-color">
                    <div class="row">
                        <div class="col-sm-2 align-self-center">
                            <p><a href="<?=base_url()?>justificantes_masivos/detalle/<?=$justificantes_masivos_item['cve_justificante_masivo']?>"><?= date('d/m/y', strtotime($justificantes_masivos_item['fecha'])) ?></a></p>
                        </div>
                        <div class="col-sm-5 align-self-center">
                            <p><?= $justificantes_masivos_item['desc_justificante_masivo'] ?></p>
                        </div>
                        <div class="col-sm-2 align-self-center">
                            <p><?= $justificantes_masivos_item['tipo'] ?></p>
                        </div>
                        <div class="col-sm-1">
                            <?php 
                            $item_eliminar = $justificantes_masivos_item['cve_justificante_masivo'] . ' '. date('d/m/y', strtotime($justificantes_masivos_item['fecha'])); 
                            $url = base_url() . "justificantes_masivos/eliminar/". $justificantes_masivos_item['cve_justificante_masivo']; 
                            ?>
                            <p><a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i>
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

