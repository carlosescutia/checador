<div class="card mb-3">
    <div class="card-header card-sistema text-center">
        <strong>Justificantes</strong>
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
            <?php foreach ($justificantes_empleado as $justificantes_empleado_item) { ?>
                <tr>
                    <td>
                        <a href="<?=base_url()?>justificantes/detalle_justificante/<?=$justificantes_empleado_item['cve_justificante']?>"><?= date('d/m/y', strtotime($justificantes_empleado_item['fecha'])) ?></a>
                    </td>
                    <td>
                        <?= is_null($justificantes_empleado_item['fech_fin']) ? "": date('d/m/y', strtotime($justificantes_empleado_item['fech_fin'])) ?>
                    </td>
                    <td>
                        <?= $justificantes_empleado_item['tipo'] ?>
                    </td>
                    <td>
                        <?= $justificantes_empleado_item['nom_eventualidad'] ?>
                    </td>
                    <td>
                        <?php 
                            $item_eliminar = date('d/m/Y', strtotime($justificantes_empleado_item['fecha'])) . ' - ' . $justificantes_empleado_item['tipo'];
                            $url = base_url() . "justificantes/eliminar/". $justificantes_empleado_item['cve_justificante']; 
                        ?>
                        <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                            <a href="#dlg_borrar" class="d-print-none" data-bs-toggle="modal" onclick="pass_data('<?=$item_eliminar?>', '<?=$url?>')" ><i class="bi bi-x-circle boton-eliminar" ></i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if (in_array('99', $accesos_sistema_rol)) { ?>
        <div class="card-footer text-end">
            <a class="btn btn-primary btn-sm d-print-none" href="<?=base_url()?>justificantes/nuevo_justificante/<?=$incidentes_empleado_item['cve_empleado']?>">Agregar</a>
        </div>
    <?php } ?>
</div>
