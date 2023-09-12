<div class="d-flex flex-column text-center">
    <h1>
        <?= esc($title) ?>
    </h1>
    <div class="card text-bg-light mb-3" style="max-width: 25rem;">
        <div class="card-body">
            <div class="row row-cols-1 gap-2">
                <?php foreach ($items as $bodyItem): ?>
                    <div class="d-flex flex-row justify-content-around align-items-center">
                        <a href="<?= esc($bodyItem->link) ?>" class="btn btn-primary p-2 flex-grow-1 m-1"><?= esc($bodyItem->text) ?></a>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>