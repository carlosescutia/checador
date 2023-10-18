<div class="card mt-0 mb-3">
    <div class="card-header card-sistema text-center">
        <strong>Por dia</strong>
    </div>
    <div class="card-body">
        <div class="text-center" id="canvas-holder" style="width:100%">
            <canvas id="avance_dias"></canvas>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 text-center">
                    <h5>Días con incidentes</h5>
                    <h1><?= $tot_dias_incidentes ?></h1>
                </div>
                <div class="col-md-6 text-center">
                    <h5>Días hábiles</h5>
                    <h1><?= $tot_dias_habiles ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>

