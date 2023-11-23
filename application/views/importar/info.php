<div class="card mt-0 mb-3">
    <div class="card-header card-sistema text-center">
        <strong>Asistencias</strong>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <h5>Registros</h5>
            <div class="col-sm-10 offset-sm-1 mt-2">
                <table class="table table-striped table-sm">
                    <tbody>
                        <tr>
                            <td>Totales</td>
                            <td class="text-center"><?=$tot_asistencias?></td>
                        </tr>
                        <tr>
                            <td>Más antiguo</td>
                            <td class="text-center"><?=$asistencia_antigua?></td>
                        </tr>
                        <tr>
                            <td>Más reciente</td>
                            <td class="text-center"><?=$asistencia_reciente?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <h5>Configuración</h5>
            <div class="col-sm-10 offset-sm-1 mt-2">
                <table class="table table-striped table-sm">
                    <tbody>
                        <tr>
                            <td>Días a cargar</td>
                            <td class="text-center"><?=$dias_cargar?></td>
                        </tr>
                        <tr>
                            <td>Tolerancia de retardo</td>
                            <td class="text-center"><?=$tolerancia_retardo?></td>
                        </tr>
                        <tr>
                            <td>Tolerancia de asistencia</td>
                            <td class="text-center"><?=$tolerancia_asistencia?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

