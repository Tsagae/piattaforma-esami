<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiungi uno studente</h4>
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

            <label for="cdl" class="form-label">Corso di laurea</label>
            <select class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="cdl" required="" value="<?= set_value('cdl') ?>">
                <option value="">Choose...</option>
                <?php foreach ($allCdl as $cdl): ?>
                    <option value="<?= esc($cdl->id_cdl) ?>"><?= esc($cdl->id_cdl) ?> <?= esc($cdl->nome) ?></option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback">
                Seleizionare un cdl valido
            </div>
        </div>
        <p><?= validation_list_errors() ?></p>

        <?php if (isset($queryError)): ?>
            <hr class="my-4">
            <div class="form-check">
                <p>
                    <?= esc($queryError) ?>
                </p>
            </div>
            <hr class="my-4">
        <?php endif; ?>
        <p>Email, utente e matricola verranno creati automaticamente</p>
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiungi</button>
    </form>
</div>