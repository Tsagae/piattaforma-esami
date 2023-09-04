<div class="d-flex flex-column">
    <div class="card text-bg-light mb-3" style="max-width: 25rem;">
        <div class="card-body">
            <div class="row row-cols-1 gap-2">
                <div class="d-flex flex-row justify-content-around">
                    <h3><?= esc($text) ?></h3>
                </div>
                <div class="d-flex flex-row justify-content-around">
                    <form method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" id="submitValue" name="submitValue" value="<?= esc($submitValue) ?>"/>
                        <button type="submit" class="btn btn-primary"><?= esc($confirmText) ?></button>
                    </form>
                    <a href="<?= esc($cancelRedirect) ?>" class="btn btn-secondary"><?= esc($cancelText) ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
