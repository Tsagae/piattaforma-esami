<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiungi un esame propedeutico</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id_insegnamento" value="<?= esc($id_insegnamento) ?>">
        <div class="row g-3">
            <div class="row g-3 mb-3">
                <label for="id_cdl" class="form-label">Corsi di laurea</label>
                <select
                    class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="id_richiesto" required="">
                    <option value="">
                        Seleziona un insegnamento
                    </option>
                    <?php foreach ($propedeutici as $propedeutico): ?>
                        <option value="<?= esc($propedeutico->id_insegnamento) ?>"><?= esc($propedeutico->nome_insegnamento) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiungi</button>
</div>

<p>
    <?= validation_list_errors() ?>
</p>

<?php if (isset($queryError)): ?>
    <hr class="my-4">
    <div class="form-check">
        <p>
            <?= esc($queryError) ?>
        </p>
    </div>
    <hr class="my-4">
<?php endif; ?>
</form>
</div>