<div class="col-sm-12 mb-3">
    <form method="post" action="<?= $curr_controller ?>">
        <div class="row">
            <div class="col">
                <select class="form-select form-select-sm" id="mes" name="mes">
                    <option value="1" <?= $mes == '1' ? 'selected' : '' ?>>Enero</option>
                    <option value="2" <?= $mes == '2' ? 'selected' : '' ?>>Febrero</option>
                    <option value="3" <?= $mes == '3' ? 'selected' : '' ?>>Marzo</option>
                    <option value="4" <?= $mes == '4' ? 'selected' : '' ?>>Abril</option>
                    <option value="5" <?= $mes == '5' ? 'selected' : '' ?>>Mayo</option>
                    <option value="6" <?= $mes == '6' ? 'selected' : '' ?>>Junio</option>
                    <option value="7" <?= $mes == '7' ? 'selected' : '' ?>>Julio</option>
                    <option value="8" <?= $mes == '8' ? 'selected' : '' ?>>Agosto</option>
                    <option value="9" <?= $mes == '9' ? 'selected' : '' ?>>Septiembre</option>
                    <option value="10" <?= $mes == '10' ? 'selected' : '' ?>>Octubre</option>
                    <option value="11" <?= $mes == '11' ? 'selected' : '' ?>>Noviembre</option>
                    <option value="12" <?= $mes == '12' ? 'selected' : '' ?>>Diciembre</option>
                </select>
            </div>
            <div class="col">
                <input type="number" class="form-control form-control-sm" id="anio" name="anio" min="2022" max="2030" value="<?=$anio?>">
            </div>
            <div class="col">
                <button class="btn btn-success btn-sm d-print-none">Cambiar</button>
            </div>
        </div>
    </form>
</div>
