<div class="card mb-5">
    <div class="card-header card-sistema text-center">
        <strong>Dias inhábiles</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">fecha</th>
                    <th scope="col">detalle</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($dias_inhabiles as $dias_inhabiles_item) { ?>
                <tr>
                    <td>
                        <a href="<?=base_url()?>dias_inhabiles/detalle/<?=$dias_inhabiles_item['cve_dia_inhabil']?>"><?= date('d/m/y', strtotime($dias_inhabiles_item['fecha'])) ?></a>
                    </td>
                    <td>
                        <?= $dias_inhabiles_item['desc_dia_inhabil'] ?>
                    </td>
                    <td>
                        <?php 
                            $item_eliminar = $dias_inhabiles_item['fecha'] . ' ' . $dias_inhabiles_item['desc_dia_inhabil']; 
                            $url = base_url() . "dias_inhabiles/eliminar/". $dias_inhabiles_item['cve_dia_inhabil']; 
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
            <form method="post" action="<?= base_url() ?>dias_inhabiles/nuevo">
                <button type="submit" class="btn btn-primary">Nuevo</button>
            </form>
        </div>
    <?php } ?>
</div>
