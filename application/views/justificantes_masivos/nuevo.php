<main role="main" class="ml-sm-auto px-4">
    <?php $url_padre = $this->session->userdata('url_padre'); ?>

    <form method="post" action="<?= base_url() ?>justificantes_masivos/guardar">

        <div class="col-sm-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-sm-10">
                    <h1 class="h2">Nuevo justificante masivo</h1>
                </div>
                <?php if (in_array('99', $accesos_sistema_rol)) { ?>
                <div class="col-sm-2 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                <?php } ?>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="fecha" class="col-sm-3 col-form-label">Fecha inicial</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="fecha" id="fecha">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fech_fin" class="col-sm-3 col-form-label">Fecha final</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="fech_fin" id="fech_fin">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipo" class="col-sm-3 col-form-label">Tipo</label>
                        <div class="col-sm-4">
                            <select class="form-select" name="tipo" id="tipo" onchange="cambia_estado_periodos()">
                                <option value="D" >Día</option>
                                <option value="E" >Entrada</option>
                                <option value="S" >Salida</option>
                                <option value="V" >Vacaciones</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row controles-periodo">
                        <label for="id_periodo" class="col-sm-3 col-form-label">Período</label>
                        <div class="col-sm-4">
                            <select class="form-select" name="id_periodo" id="id_periodo">
                                <option value=""></option>
                                <?php foreach ($periodos as $periodos_item): ?>
                                    <option value="<?= $periodos_item['id_periodo'] ?>"><?=$periodos_item['nom_periodo']?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row controles-periodo">
                        <label for="anio" class="col-sm-3 col-form-label">Año</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" name="anio" id="anio">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="desc_justificante_masivo" class="col-sm-3 col-form-label">Descripción</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="desc_justificante_masivo" id="desc_justificante_masivo" rows="6"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-auto">
                        <label class="h5 me-5 mb-3">Aplicar a:</label>
                        <button id="btn_todos" type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-check2-square me-2"></i>Todos</button>
                        <button id="btn_ninguno" type="button" class="btn btn-outline-secondary btn-sm"><i class="bi bi-square me-2"></i>Ninguno</button>
                    </div>
                    <div class="row">
                        <?php
                            $tam = sizeof($empleados);
                            $emp1 = array_slice($empleados, 0, $tam/2);
                            $emp2 = array_slice($empleados, $tam/2, $tam);
                        ?>
                        <div class="col-sm-6">
                            <?php foreach ($emp1 as $emp1_item): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="chk<?=$emp1_item['cve_empleado']?>" name="chk<?=$emp1_item['cve_empleado']?>">
                                    <label class="form-check-label" for="chk<?=$emp1_item['cve_empleado']?>">
                                        <?= $emp1_item['nom_empleado'] ?>
                                    </label>
                                </div>
                            <?php endforeach ?>
                        </div>
                        <div class="col-sm-6">
                            <?php foreach ($emp2 as $emp2_item): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="chk<?=$emp2_item['cve_empleado']?>" name="chk<?=$emp2_item['cve_empleado']?>">
                                    <label class="form-check-label" for="chk<?=$emp2_item['cve_empleado']?>">
                                        <?= $emp2_item['nom_empleado'] ?>
                                    </label>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>


    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=$url_padre ?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>
<script>
    function cambia_estado_periodos() {
        if ( ["V"].includes($("#tipo").val()) ) {
            $(".controles-periodo").removeClass("d-none");
        } else {
            $(".controles-periodo").addClass("d-none");
        }
    }

    $(document).ready(function() {

        cambia_estado_periodos();

        $('#btn_todos').click(function() {
            $('input').prop('checked', true);
        });

        $('#btn_ninguno').click(function() {
            $('input').prop('checked', false);
        });

    });
</script>
