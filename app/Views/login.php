<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('error') ?>
<form action="/login" method="post">
    <div class="d-flex flex-column mb-3">
        <?= csrf_field() ?>
        <div class="d-flex flex-column mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= set_value('email') ?>"
                   placeholder="nome.cognome@unimips.it">
            <div class="invalid-feedback">
                Mail obbligatoria.
            </div>
        </div>

        <div class="d-flex flex-column mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" cols="45" rows="4"
                   value="<?= set_value('password') ?>">
            <div class="invalid-feedback">
                Password obbligatoria.
            </div>
        </div>

        <input type="submit" class="w-100 btn btn-primary btn-lg" name="submit" value="Login">
        <?= validation_list_errors() ?>
    </div>
</form>
<p><?= esc($userError) ?></p>