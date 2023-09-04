<?= session()->getFlashdata('error') ?>
<?php
$formValues = [];
if (isset($cdl)) {
    $formValues = [
        'id_cdl' => esc($cdl->id_cdl),
        'nome' => esc($cdl->nome),
        'tipo' => esc($cdl->tipo),
    ];
} else {
    $formValues = [
        'id_cdl' => esc(set_value('id_cdl')),
        'nome' => esc(set_value('nome')),
        'tipo' => esc(set_value('tipo')),
    ];
} ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Modifica corso di laurea</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>

        <input type="hidden" id="id_cdl" name="id_cdl" value="<?= $formValues['id_cdl'] ?>"/>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="" value="<?= $formValues['nome'] ?>"
                       required="">
                <div class="invalid-feedback">
                    Nome obbligatorio.
                </div>
            </div>
        </div>
        <div class="row g-3">
            <label for="tipo" class="form-label">Tipo di laurea</label>
            <select class="form-select dropdown-menu d-block position-static pt-0 mx-0 rounded-3 shadow overflow-hidden w-280px"
                    data-bs-theme="light" name="tipo" required="" value="<?= set_value('tipo') ?>">
                <option value="">Choose...</option>
                <?php foreach ($tipi as $tipo): ?>
                    <option value="<?= esc($tipo->tipo) ?>"><?= esc($tipo->tipo) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <?= validation_list_errors(); ?>
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