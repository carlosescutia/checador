<main role="main" class="ml-sm-auto px-4">

    <form method="post" action="<?= base_url() ?>justificantes/guardar">

        <div class="col-md-12 mb-3 pb-2 pt-3 border-bottom">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h2">Nuevo justificante</h1>
                </div>
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group row">
                <label for="fecha" class="col-sm-2 col-form-label">Fecha</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="fecha" id="fecha" value="<?= $fecha ?>" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="tipo" class="col-sm-2 col-form-label">Tipo</label>
                <div class="col-sm-2">
                    <select class="form-select" name="tipo" id="tipo">
                        <option value="D" >Día</option>
                        <option value="E" >Entrada</option>
                        <option value="S" >Salida</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="detalle" class="col-sm-2 col-form-label">Detalle</label>
                <div class="col-sm-4">
                    <textarea class="form-control" name="detalle" id="detalle" rows="4"></textarea>
                </div>
            </div>
        </div>

        <input type="hidden" name="cve_empleado" id="cve_empleado" value="<?=$cve_empleado?>">
        <input type="hidden" name="fecha" id="fecha" value="<?=$fecha?>">

    </form>


    <hr />

    <div class="form-group row">
        <div class="col-sm-10">
            <a href="<?=base_url()?>admin/empleados_detalle/<?=$cve_empleado?>" class="btn btn-secondary">Volver</a>
        </div>
    </div>

</main>