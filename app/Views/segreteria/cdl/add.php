<?= session()->getFlashdata('error') ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Aggiungi un corso di laurea</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="id_cdl" class="form-label">Codice</label>
                <input type="text" class="form-control" name="id_cdl" placeholder=""
                       value="<?= set_value('id_cdl') ?>" required="">
                <div class="invalid-feedback">
                    Codice obbligatorio.
                </div>
            </div>
            <div class="col-sm-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="" value="<?= set_value('nome') ?>"
                       required="">
                <div class="invalid-feedback">
                    Nome obbligatorio.
                </div>
            </div>
        </div>
        <label for="tipo" class="form-label">Tipo di laurea</label>
        <select class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                data-bs-theme="light" name="tipo" required="" value="<?= set_value('tipo') ?>">
            <option value="">Choose...</option>
            <?php foreach ($tipi as $tipo): ?>
                <option value="<?= esc($tipo->tipo) ?>"><?= esc($tipo->tipo) ?></option>
            <?php endforeach ?>
        </select>
        <div class="invalid-feedback">
            Selezionare un tipo valido
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
        <button class="w-100 btn btn-primary btn-lg" type="submit">Aggiungi</button>
    </form>
</div>