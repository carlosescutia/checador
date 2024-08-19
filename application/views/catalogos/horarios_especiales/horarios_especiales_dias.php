<div class="card mt-3 mb-3">
    <div class="card-header text-white bg-primary">
        Dias especiales
    </div>
    <div class="card-body">
        <ul>
            <?php foreach( $horario_especial_dias as $horario_especial_dias_item) { ?>
            <li>
            <div class="row">
                <div class="col-sm-2">
                    <?= get_nom_dia_num($horario_especial_dias_item['cve_dia']) ?>
                </div>
                <div class="col-sm-4">
                    <?= $horario_especial_dias_item['desc_horario'] ?>
                </div>
                <div class="col-sm-2">
                    <?php 
                    $item_eliminar = get_nom_dia_num($horario_especial_dias_item['cve_dia']) . ' - ' . $horario_especial_dias_item['desc_horario'];
                    $url = base_url() . "horarios_especiales_dias/eliminar/". $horario_especial['id_horario_especial'].'/'.$horario_especial_dias_item['cve_dia']; 
                    ?>
                    <a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i>
                    </a>
                </div>
            </div>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="card-footer text-end">
        <form method="post" action="<?= base_url() ?>horarios_especiales_dias/guardar">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select" name="cve_dia" id="cve_dia">
                        <option value="1">Lun</option>
                        <option value="2">Mar</option>
                        <option value="3">Mie</option>
                        <option value="4">Jue</option>
                        <option value="5">Vie</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <select class="form-select" name="cve_horario" id="cve_horario">
                        <?php foreach ($horarios as $horarios_item) { ?>
                        <option value="<?= $horarios_item['cve_horario'] ?>"><?= $horarios_item['desc_horario'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" id="id_horario_especial" name="id_horario_especial" value="<?= $horario_especial['id_horario_especial'] ?>">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </form>
    </div>
</div>
