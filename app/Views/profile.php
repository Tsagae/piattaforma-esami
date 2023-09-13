<?= session()->getFlashdata('error') ?>
<?php
$formValues = [];
if (isset($user)) {
    $formValues = [
        'password' => '',
    ];
} else {
    $formValues = [
        'password' => esc(set_value('password')),
    ];
} ?>
<div class="col-md-7 col-lg-8">
    <h4 class="mb-3">Modifica profilo</h4>
    <div class="row g-3">
        <div class="col-sm-6">
            <p>
                <?= esc($user->nome) ?>
            </p>
        </div>
        <div class="col-sm-6">
            <p>
                <?= esc($user->cognome) ?>
            </p>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-sm-6">
            <p>
                <?= esc($user->email) ?>
            </p>
        </div>
    </div>
    <form class="needs-validation" novalidate="" method="post">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-sm-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" placeholder=""
                       value="<?= set_value('password') ?>" required="">
                <p>Se lasciata vuota la password non verrà cambiata</p>
            </div>
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

        <button class="w-100 btn btn-primary btn-lg" type="submit">Modifica Password</button>
    </form>
</div>