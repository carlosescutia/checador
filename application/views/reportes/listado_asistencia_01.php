<main role="main" class="ml-sm-auto px-4 mb-3 col-print-12">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <form method="post" action="<?= base_url() ?>reportes/listado_asistencia_01">
                <div class="row">
                    <div class="col-sm-8 text-start">
                        <h1 class="h2">Asistencia</h1>
                    </div>
                    <div class="col-sm-4 text-end">
                        <button formaction="<?= base_url() ?>reportes/listado_asistencia_01_csv" class="btn btn-primary d-print-none">Exportar a excel</button>
                        <a href="javascript:window.print()" class="btn btn-primary d-print-none">Generar pdf</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div style="min-height: 46vh">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Empleado</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($asistencias as $asistencias_item) { ?>
                        <tr>
                            <td><?= $asistencias_item['cve_asistencia'] ?></td>
                            <td><?= $asistencias_item['nom_empleado'] ?></td>
                            <td><?= empty($asistencias_item['fecha']) ? '' : date('d/m/y', strtotime($asistencias_item['fecha'])) ?></td>
                            <td><?= empty($asistencias_item['hora']) ? '' : date('H:i', strtotime($asistencias_item['hora'])) ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
