<div class="card mt-0 mb-3">
    <div class="card-header card-sistema text-center">
        <strong>Por dia</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <h5 class="text-center">Incidentes</h5>
            <div class="row">
                <div class="col-sm-10 offset-sm-1" id="canvas-holder">
                    <canvas id="avance_dias"></canvas>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <h6><a href="<?=base_url()?>admin/fechas_incidentes">Días con incidentes</a></h6>
                        <h1><?= $tot_dias_incidentes ?></h1>
                    </div>
                    <div class="col-md-6 text-center">
                        <h6>Días con información</h6>
                        <h1><?= $tot_dias_info ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <h5 class="text-center">Carga de información</h5>
            <div class="row">
                <div class="col-sm-10 offset-sm-1" id="canvas-holder">
                    <canvas id="carga_info"></canvas>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <h6>Días con información</h6>
                        <h1><?= $tot_dias_info ?></h1>
                    </div>
                    <div class="col-md-6 text-center">
                        <h6>Días hábiles</h6>
                        <h1><?= $tot_dias_habiles ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
