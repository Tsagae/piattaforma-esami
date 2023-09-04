<?= session()->getFlashdata('error') ?>
<?= csrf_field() ?>
<?php
$formValues = [];
if (isset($studente)) {
    $formValues = [
        'nome' => esc($studente->nome),
        'cognome' => esc($studente->cognome),
        'email' => esc($studente->email),
        'matricola' => esc($studente->matricola)
    ];
} else {
    $formValues = [
        'nome' => esc(set_value('nome')),
        'cognome' => esc(set_value('cognome')),
        'email' => esc(set_value('email')),
        'matricola' => esc(set_value('matricola')),
    ];
} ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Modifica studente</h4>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>

        <input type="hidden" id="matricola" name="matricola" value="<?= $formValues['matricola'] ?>"/>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" placeholder="" value="<?= $formValues['nome'] ?>"
                       required="">
                <div class="invalid-feedback">
                    Nome obbligatorio.
                </div>
            </div>

            <div class="col-sm-6">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" class="form-control" name="cognome" placeholder=""
                       value="<?= $formValues['cognome'] ?>" required="">
                <div class="invalid-feedback">
                    Cognome obbligatorio.
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder=""
                       value="<?= $formValues['email'] ?>" required="">
                <div class="invalid-feedback">
                    Email obbligatoria.
                </div>
            </div>
            <div class="col-sm-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder=""
                       value="<?= set_value('password') ?>" required="">
                <p>Se lasciata vuota la password non verrà cambiata</p>
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

        <button class="w-100 btn btn-primary btn-lg" type="submit">Modifica</button>
    </form>
</div>