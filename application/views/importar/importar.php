        <div class="row">
            <div class="col-sm-10 offset-sm-1 mb-5">
                <h3>1. Cargar archivo del checador</h3>
                <?php 
                $nombre_archivo = 'checador.csv'
                ?>
                <form method="post" enctype="multipart/form-data" action="<?=base_url()?>archivos/archivo_checador">
                    <div class="row text-danger">
                        <?php if ($error) { 
                        echo $error;
                        } ?>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-md-4">
                            <input type="file" class="form-control-file" name="subir_archivo">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="submit" class="btn btn-success btn-sm">Cargar archivo</button>
                        </div>
                    </div>
                    <input type="hidden" name="nombre_archivo" value="<?=$nombre_archivo?>">
                </form>
            </div>


            <?php 
            $nombre_archivo = 'checador.csv';
            $nombre_archivo_fs = './doc/' . $nombre_archivo;
            if ( file_exists($nombre_archivo_fs) ) { ?>
                <div class="col-sm-10 offset-sm-1">
                    <h3>2. Datos del archivo cargado:</h3>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <?php
                                    $contador = 0;
                                    $file = fopen($nombre_archivo_fs, "r");
                                    $linea = fgetcsv($file); ?>
                                    <tr>
                                        <th scope="col"><?= $linea[0] ?></th>
                                        <th scope="col"><?= $linea[1] ?></th>
                                        <th scope="col"><?= $linea[2] ?></th>
                                        <th scope="col"><?= $linea[3] ?></th>
                                        <th scope="col"><?= $linea[4] ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while(!feof($file)) {
                                        $contador += 1;
                                        $linea = fgetcsv($file);
                                        if ($linea) { ?>
                                            <tr>
                                                <td><?= $linea[0] ?></td>
                                                <td><?= $linea[1] ?></td>
                                                <td><?= $linea[2] ?></td>
                                                <td><?= $linea[3] ?></td>
                                                <td><?= $linea[4] ?></td>
                                            </tr>
                                        <?php }
                                    } 
                                    fclose($file);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form method="post" enctype="multipart/form-data" action="<?=base_url()?>importar/guardar">
                        <div class="row text-danger">
                            <?php if ($error) { 
                            echo $error;
                            } ?>
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-primary btn-sm">Importar datos</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>


        </div>
