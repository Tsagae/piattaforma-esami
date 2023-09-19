<form method="get">
    <select name="idcdl" onchange="this.form.submit()">
        <?php if (!empty($firstOption)): ?>
            <option value="<?= esc($firstOption->value) ?>"><?= esc($firstOption->text) ?></option>
        <?php endif; ?>

        <?php foreach ($options as $option): ?>
            <option value="<?= esc($option->value) ?>"><?= esc($option->text) ?></option>
        <?php endforeach ?>
    </select>
</form>