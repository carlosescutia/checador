<div class="card mb-5">
    <div class="card-header card-sistema text-center">
        <strong>Vacaciones</strong>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">desde</th>
                    <th scope="col">hasta</th>
                    <th scope="col">detalle</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($vacaciones_empleado as $vacaciones_empleado_item) { ?>
                <?php
                    $url = '#';
                    if ($vacaciones_empleado_item['tipo_justificante'] == 'ji'):
                        $url = base_url() . 'justificantes/detalle_vacacion/' . $vacaciones_empleado_item['cve_justificante'] ;
                    endif;
                    if ($vacaciones_empleado_item['tipo_justificante'] == 'jm'):
                        $url = base_url() . 'justificantes_masivos/detalle/' . $vacaciones_empleado_item['cve_justificante'] ;
                    endif;
                ?>
                <tr>
                    <td>
                        <a href="<?= $url ?>"><?= date('d/m/y', strtotime($vacaciones_empleado_item['fecha'])) ?></a>
                    </td>
                    <td>
                        <?= is_null($vacaciones_empleado_item['fech_fin']) ? "": date('d/m/y', strtotime($vacaciones_empleado_item['fech_fin'])) ?>
                    </td>
                    <td>
                        <?= $vacaciones_empleado_item['detalle'] ?> (<?= $vacaciones_empleado_item['dias'] ?>d)
                    </td>
                    <td>
                        <?php 
                        $item_eliminar = date('d/m/Y', strtotime($vacaciones_empleado_item['fecha'])) . ' - ' . $vacaciones_empleado_item['tipo'];
                        $url = base_url() . "justificantes/eliminar/". $vacaciones_empleado_item['cve_justificante']; 
                        ?>
                        <?php if ( in_array('99', $accesos_sistema_rol) and ($vacaciones_empleado_item['tipo_justificante'] == 'ji') ) { ?>
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
        <a class="btn btn-primary btn-sm d-print-none" href="<?=base_url()?>justificantes/nueva_vacacion/<?=$incidentes_empleado_item['cve_empleado']?>">Agregar</a>
    </div>
    <?php } ?>
</div>
