<?php if (!empty($items) && is_array($items)): ?>
    <?php foreach ($items as $item): ?>
        <div class="card mb-3">
            <div class="card-header">
                <?= esc($item->head) ?>
            </div>
            <div class="card-body d-flex flex-row justify-content-center align-items-center">
                <span class="m-1">
                    <?php foreach ($item->body as $bodyItem): ?>
                        <?= esc($bodyItem) ?>
                    <?php endforeach ?>
                </span>
                <?php foreach ($item->buttons as $button): ?>
                    <a href="<?= esc($button->link) ?>" class="<?= esc($button->style) ?>"><?= esc($button->text) ?></a>
                <?php endforeach ?>
            </div>
        </div>
    <?php endforeach ?>

<?php else: ?>

    <h3>
        <?= esc($noRecordsText) ?>
    </h3>

<?php endif ?>