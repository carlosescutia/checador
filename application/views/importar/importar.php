<div class="col-sm-11 offset-sm-1">
    <div class="col mb-5">
        <h3>1. Seleccione archivo a importar</h3>
        <?php 
        $nombre_archivo = 'checador.csv'
        ?>
        <form method="post" enctype="multipart/form-data" action="<?=base_url()?>archivos/archivo_checador">
            <div class="row text-danger">
                <?php if ($error) { 
                echo $error;
                } ?>
            </div>
            <?php if (in_array('99', $accesos_sistema_rol)) { ?>
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <input type="file" class="form-control-file" name="subir_archivo">
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-success btn-sm">Verificar datos</button>
                </div>
            </div>
            <?php } ?>
            <input type="hidden" name="nombre_archivo" value="<?=$nombre_archivo?>">
        </form>
    </div>


    <?php 
    $nombre_archivo = 'checador.csv';
    $nombre_archivo_fs = './doc/' . $nombre_archivo;
    if ( file_exists($nombre_archivo_fs) ) { ?>
    <div class="col mb-5">
        <h3>2. Datos que contiene el archivo:</h3>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col" class="col-1">clave</th>
                            <th scope="col" class="col-1">fecha</th>
                            <th scope="col" class="col-1">hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 0;
                        $file = fopen($nombre_archivo_fs, "r");
                        while(!feof($file) and $contador < 5) {
                        $contador += 1;
                        $linea = fgetcsv($file, 0, "\t");
                        if ( $linea and !is_null($linea[0]) ) { ?>
                        <tr>
                            <td><?= $linea[0] ?></td>
                            <td><?= substr($linea[1], 0, strpos($linea[1], ' ')); ?></td>
                            <td><?= substr($linea[1], strpos($linea[1], ' '), strlen($linea[1])); ?></td>
                        </tr>
                        <?php }
                        } 
                        fclose($file);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col">
        <h3>3. Finalizar importación</h3>
        <form method="post" enctype="multipart/form-data" action="<?=base_url()?>importar/guardar">
            <div class="row text-danger">
                <?php if ($error) { 
                echo $error;
                } ?>
            </div>
            <?php if (in_array('99', $accesos_sistema_rol)) { ?>
            <div class="col text-center">
                <button type="submit" class="btn btn-primary">Importar datos</button>
            </div>
            <?php } ?>
        </form>
    </div>
    <?php } ?>
</div>
