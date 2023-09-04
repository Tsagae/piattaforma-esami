<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiungi un docente</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="" value="<?= set_value('nome') ?>"
                       required="">
                <div class="invalid-feedback">
                    Nome obbligatorio.
                </div>
            </div>

            <div class="col-sm-6">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" class="form-control" name="cognome" placeholder=""
                       value="<?= set_value('cognome') ?>" required="">
                <div class="invalid-feedback">
                    Cognome obbligatorio.
                </div>
            </div>
        </div>
        <p><?=  validation_list_errors() ?></p>

        <?php if (isset($queryError)): ?>
            <hr class="my-4">
            <div class="form-check">
                <p>
                    <?= esc($queryError) ?>
                </p>
            </div>
            <hr class="my-4">
        <?php endif; ?>
        <p>Email, e utente verranno creati automaticamente</p>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiungi</button>
    </form>
</div>