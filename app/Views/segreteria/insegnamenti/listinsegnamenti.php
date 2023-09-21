<div class="d-flex flex-row justify-content-center align-items-center">
    <h1 class="m-1">Insegnamenti</h1> <a href="/segreteria/insegnamenti/add" class="btn btn-primary m-1">+</a>
</div>
<form class="needs-validation" method="get">
    <div class="row g-3 mb-3">
        <label for="id_cdl" class="form-label">Corsi di laurea</label>
        <select onchange="this.form.submit()"
                class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                data-bs-theme="light" name="id_cdl" required="" value="<?= set_value('id_cdl') ?>">
            <option value="">
                <?php if (!empty(set_value('id_cdl'))): ?>
                    <?= esc(set_value('id_cdl')) ?>
                <?php else: ?>
                    Seleziona un corso di laurea
                <?php endif ?>
            </option>
            <?php foreach ($allCdl as $cdl): ?>
                <option value="<?= esc($cdl->id_cdl) ?>"><?= esc($cdl->id_cdl) ?>  <?= esc($cdl->nome) ?></option>
            <?php endforeach ?>
        </select>
    </div>
</form>