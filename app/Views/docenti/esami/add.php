<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiungi un esame</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="insegnamento" class="form-label">Insegnamento</label>
                <p name="insegnamento">
                    <?= esc($insegnamento->nome) ?>
                </p>
                <input type="hidden" name="id_insegnamento" value="<?= esc($insegnamento->id_insegnamento) ?>">
            </div>
            <div class="col-sm-6">
                <label for="docente" class="form-label">Docente</label>
                <p name="docente">
                    <?= esc($docente->nome) ?>
                    <?= esc($docente->cognome) ?>
                </p>
                <input type="hidden" name="id_docente" value="<?= esc($docente->id_docente) ?>">
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
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiungi</button>
    </form>
</div>