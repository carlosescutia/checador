<div class="card mt-0 mb-3">
    <div class="card-header card-sistema text-center">
        <strong>Por empleado</strong>
    </div>
    <div class="card-body">
        <div class="text-center" id="canvas-holder" style="width:100%">
            <canvas id="avance_empleados"></canvas>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 text-center">
                    <h5><a href="<?=base_url()?>admin/empleados_incidentes/">Empleados con incidentes</a></h5>
                    <h1><?= $tot_empleados_incidentes ?></h1>
                </div>
                <div class="col-md-6 text-center">
                    <h5><a href="<?=base_url()?>admin/empleados_activos/">Empleados activos</a></h5>
                    <h1><?= $tot_empleados_activos ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
