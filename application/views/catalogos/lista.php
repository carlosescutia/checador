<main role="main" class="ml-sm-auto px-4 mb-3">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1>Catálogos</h1>
    </div>
    <div class="row">
        <div class="col-md-9 p-3">
            <h2>Aplicación</h2>
            <div class="row mb-3 gy-3">
                <?php if (in_array('501', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "empleados/boton.php"; ?>
                    </div>
                <?php } ?>
                <?php if (in_array('502', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "horarios/boton.php"; ?>
                    </div>
                <?php } ?>
                <?php if (in_array('506', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "horarios_especiales/boton.php"; ?>
                    </div>
                <?php } ?>
                <?php if (in_array('505', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "eventualidades/boton.php"; ?>
                    </div>
                <?php } ?>
                <?php if (in_array('507', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "periodos/boton.php"; ?>
                    </div>
                <?php } ?>
                <?php if (in_array('504', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "organizaciones/boton.php"; ?>
                    </div>
                <?php } ?>
                <?php if (in_array('503', $accesos_sistema_rol)) { ?>
                    <div class="col-md-4">
                        <?php include "parametros_sistema/boton.php"; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if ($cve_rol == 'adm') { ?>
        <div class="col-md-3 p-3 border bg-light">
            <h2>Sistema</h2>
            <div class="row mb-3">
                <div class="col-md-12">
                    <?php include "usuarios/boton.php" ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <?php include "roles/boton.php" ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <?php include "opciones_sistema/boton.php" ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <?php include "accesos_sistema/boton.php" ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <?php include "opciones_publicas/boton.php" ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</main>
