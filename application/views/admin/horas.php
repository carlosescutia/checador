<div class="card mb-5">
    <div class="card-header card-sistema text-center">
        <strong>Horas</strong>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col">
                Del mes: <?= $horas_a_cubrir_empleado ?>
            </div>
            <div class="col">
                Trabajadas: <?= $horas_trabajadas_empleado ?>
            </div>
            <div class="col">
                <?= number_format($horas_trabajadas_empleado / $horas_a_cubrir_empleado * 100, 0) ?>%
            </div>
        </div>
    </div>
</div>

