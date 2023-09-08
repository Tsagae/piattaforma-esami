<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Modifica un esame</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="insegnamento" class="form-label">Insegnamento</label>
                <p name="insegnamento">
                    <?= esc($esame->nome_insegnamento) ?>
                </p>
                <input type="hidden" name="id_esame" value="<?= esc($esame->id_esame) ?>">
            </div>
            <div class="col-sm-6">
                <label for="docente" class="form-label">Docente</label>
                <p name="docente">
                    <?= esc($esame->nome_docente) ?>
                    <?= esc($esame->cognome_docente) ?>
                </p>
                <input type="hidden" name="id_docente" value="<?= esc($esame->id_docente) ?>">
            </div>
        </div>
        <label for="data" class="form-label">Data esame</label>
        <input type="date" class="form-control" name="data" required="">
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
        <button class="w-100 btn btn-primary btn-lg" type="submit">Modifica</button>
    </form>
</div>