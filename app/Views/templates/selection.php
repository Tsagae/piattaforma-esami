<form method="get">
    <select name="idcdl" class="form-select d-block position-static pt-0 m-3 rounded-3 shadow overflow-hidden w-280px" onchange="this.form.submit()">
        <?php if (!empty($firstOption)): ?>
            <option value="<?= esc($firstOption->value) ?>"><?= esc($firstOption->text) ?></option>
        <?php endif; ?>

        <?php foreach ($options as $option): ?>
            <option value="<?= esc($option->value) ?>"><?= esc($option->text) ?></option>
        <?php endforeach ?>
    </select>
</form>