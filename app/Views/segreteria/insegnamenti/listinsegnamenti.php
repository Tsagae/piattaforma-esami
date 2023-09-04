<div class="d-flex flex-row justify-content-center align-items-center">
    <h1 class="m-1">Insengamenti</h1> <a href="/segreteria/insegnamenti/add" class="btn btn-primary m-1">+</a>
</div>
<form class="needs-validation" method="post">
    <?= csrf_field() ?>
    <div class="row g-3 mb-3">
        <label for="tipo" class="form-label">Corsi di laurea</label>
        <select onchange="this.form.submit()"
            class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
            data-bs-theme="light" name="id_cdl" required="" value="<?= set_value('id_cdl') ?>">
            <option value="">
                <?php if (!empty(set_value('id_cdl'))): ?>
                    <?= esc(set_value('id_cdl')) ?>
                <?php else: ?>
                    <span>Seleziona un corso di laurea</span>
                <?php endif ?>
            </option>
            <?php foreach ($allCdl as $cdl): ?>
                <option value="<?= esc($cdl->id_cdl) ?>"><?= esc($cdl->id_cdl) ?>     <?= esc($cdl->nome) ?></option>
            <?php endforeach ?>
        </select>
    </div>
</form>


<?php if (!empty($insegnamenti) && is_array($insegnamenti)): ?>
    <?php foreach ($insegnamenti as $insegnamento): ?>
        <div class="card mb-3">
            <div class="card-header">
                <?= esc($insegnamento->id_insegnamento) ?>
                <?= esc($insegnamento->nome) ?>
            </div>
            <div class="card-body d-flex flex-row justify-content-center align-items-center">
                <span class="m-1">
                    <?= esc($insegnamento->semestre) ?> Semestre
                    <?= esc($insegnamento->docente_nome) ?>
                    <?= esc($insegnamento->docente_cognome) ?>
                </span>
                <a href="/segreteria/insegnamento/edit?id=<?= esc($insegnamento->id_insegnamento) ?>"
                    class="btn btn-primary m-1">Modifica</a>
                <a href="/segreteria/insegnamento/delete?id=<?= esc($insegnamento->id_insegnamento) ?>"
                    class="btn btn-danger m-1">Elimina</a>
            </div>
        </div>

    <?php endforeach ?>

<?php else: ?>

    <h3>Nessun insegnamento</h3>

<?php endif ?>