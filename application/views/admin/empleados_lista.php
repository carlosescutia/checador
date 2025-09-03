<main role="main" class="ml-sm-auto px-4 mb-3">
    <?php $this->session->set_userdata('url_actual', current_url()); ?>
    <?php $this->session->set_userdata('url_padre', current_url()); ?>
    <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-4 text-left">
                    <h2><?= $titulo ?></h2>
                </div>
                <div class="col-sm-4 text-left">
                    <?php include 'selector_mes.php' ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-0 mb-3 border-0">
        <div class="card-body">
            <div class="row">

                <div class="col-sm-7">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Clave</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Horario</th>
                                <th scope="col">Incidentes</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($incidentes_empleados as $incidentes_empleados_item) { ?>
                            <tr>
                                <td>
                                    <?= $incidentes_empleados_item['cod_empleado'] ?>
                                </td>
                                <td>
                                    <a href="<?=base_url()?>admin/empleados_detalle/<?=$incidentes_empleados_item['cve_empleado']?>"><?= $incidentes_empleados_item['nom_empleado'] ?></a>
                                </td>
                                <td>
                                    <?= $incidentes_empleados_item['desc_horario'] ?>
                                </td>
                                <td class="text-center">
                                    <?= $incidentes_empleados_item['num_incidentes'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-4 offset-sm-1">
                    <?php include "dias_inhabiles.php" ?>
                    <?php include "justificantes_masivos.php" ?>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>admin" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
