<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="<?=base_url()?>img/favicon.png" sizes="16x16" type="image/png" />

        <title><?= $nom_sitio_corto ?? 'Lorem ipsum' ?></title>

        <!-- global css -->
        <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/admin.css" />

        <!-- bootstrap 5.3 -->
        <link href="<?=base_url()?>css/bootstrap.min.css" rel="stylesheet"/>
        <link href="<?=base_url()?>css/bootstrap-icons.css" rel="stylesheet"/>
        <script src="<?=base_url()?>js/bootstrap.bundle.min.js"></script>

        <!-- jquery -->
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    </head>
    <body>
        <nav class="navbar navbar-expand-sm navbar-light fixed-top d-print-block pr-3">

            <div class="container-fluid">
                <!-- logo -->
                <div class="logo_menu">
                    <img class="logo" src="<?=base_url()?>img/<?= $logo_org_sitio ?? 'logotipo.png' ?>" class="d-inline-block align-top" alt="logo">
                </div>
                <!-- titulo -->
                <div class="titulo_menu">
                    <h4 class="texto-titulo"><?= $nom_sitio_corto ?? 'Lorem ipsum' ?></h4>
                </div>

                <!-- boton para menu colapsado (pantallas pequeñas) -->
                <button class="navbar-toggler navbar-toggler-right pr-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" >
                    <span class="navbar-toggler-icon"></span>
                </button> <!-- boton menu -->

                <!-- opciones del menu -->
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="col-sm-7 mt-2">
                        <h5 class="texto-titulo"><?= $nom_sitio_largo ?? 'Lorem ipsum' ?></h5>
                        <hr class="mb-0 mt-2 pt-0 pb-0 " />
                        <ul class="navbar-nav mr-auto">
                            <?php foreach ($opciones_sistema as $opciones_sistema_item) {
                                if (in_array($opciones_sistema_item['cod_opcion'], $accesos_sistema_rol) && $opciones_sistema_item['es_menu'] ) { ?>
                                    <li class="nav-item"><a class="nav-link d-print-none" href="<?=base_url()?><?=$opciones_sistema_item['url'] ?>"><?=$opciones_sistema_item['nom_opcion'] ?></a></li>
                                <?php } 
                            } ?>
                        </ul>
                    </div>
                    <div class="col-sm-5 text-end">
                        <p class="m-2 texto-titulo"><?php echo $nom_usuario ?> · <?php echo $nom_organizacion ?> | <a class="m-2 texto-titulo d-print-none" href="<?= base_url() ?>admin/cerrar_sesion">Cerrar sesión</a></p>
                    </div>
                </div> <!-- opciones del menu -->
            </div>

        </nav>

        <main role="main" class="container-fluid ml-sm-auto px-4">
