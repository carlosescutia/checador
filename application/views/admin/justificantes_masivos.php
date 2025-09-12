<div class="card mb-5">
    <div class="card-header card-sistema text-center">
        <strong>Justificantes masivos</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">desde</th>
                    <th scope="col">hasta</th>
                    <th scope="col">tipo</th>
                    <th scope="col">eventualidad</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($justificantes_masivos as $justificantes_masivos_item) { ?>
                <tr>
                    <td>
                        <a href="<?=base_url()?>justificantes_masivos/detalle/<?=$justificantes_masivos_item['cve_justificante_masivo']?>"><?= date('d/m/y', strtotime($justificantes_masivos_item['fecha'])) ?></a>
                    </td>
                    <td>
                        <?= is_null($justificantes_masivos_item['fech_fin']) ? "": date('d/m/y', strtotime($justificantes_masivos_item['fech_fin'])) ?>
                    </td>
                    <td>
                        <?= $justificantes_masivos_item['tipo'] ?>
                    </td>
                    <td>
                        <?= $justificantes_masivos_item['nom_periodo'] . ' ' . $justificantes_masivos_item['anio'] . ' ' . $justificantes_masivos_item['desc_justificante_masivo'] ?>
                    </td>
                    <td>
                        <?php 
                            $item_eliminar = $justificantes_masivos_item['fecha'] . ' ' . $justificantes_masivos_item['desc_justificante_masivo']; 
                            $url = base_url() . "justificantes_masivos/eliminar/". $justificantes_masivos_item['cve_justificante_masivo']; 
                        ?>
                        <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                            <a href="#dlg_borrar" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if (in_array('99', $accesos_sistema_rol)) { ?>
    <div class="card-footer text-end">
        <form method="post" action="<?= base_url() ?>justificantes_masivos/nuevo">
            <button type="submit" class="btn btn-primary">Nuevo</button>
        </form>
    </div>
    <?php } ?>
</div>
