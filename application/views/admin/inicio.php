<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="col-sm-12 alternate-color">
            <div class="row">
                <div class="col-sm-10 text-left">
                    <h1 class="h2">Tablero de control</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-sm-12">
            <?php include "incidentes_totales.php"; ?>
        </div>
    </div>

    <h3 class="text-center mb-5">Avance</h3>

    <div class="row">
        <div class="col-sm-5 offset-sm-1">
            <div class="col-sm-12">
                <?php include "incidentes_empleado.php"; ?>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="col-sm-12">
                <?php include "incidentes_dia.php"; ?>
            </div>
        </div>
    </div>

    <hr />

    <?php include 'js/inicio.js'; ?>
    <?php include 'js/grafico_empleados.js'; ?>
    <?php include 'js/grafico_dias.js'; ?>
    <?php include 'js/grafico_carga.js'; ?>

</main>
