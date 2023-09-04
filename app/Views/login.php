<h2><?= esc($title) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= validation_list_errors() ?>

<form action="/login" method="post">
    <?= csrf_field() ?>

    <label for="email">Email</label>
    <input type="email" name="email" value="<?= set_value('email') ?>">
    <br>

    <label for="password">Password</label>
    <input type="password" name="password" cols="45" rows="4" value="<?= set_value('password') ?>">
    <br>

    <input type="submit" name="submit" value="Login">
</form>

<p><?= esc($userError) ?></p>