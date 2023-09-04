<div class="d-flex flex-row justify-content-center align-items-center">
    <h1 class="m-1">Corsi di laurea</h1> <a href="/segreteria/cdl/add" class="btn btn-primary m-1">+</a>
</div>
<?php if (!empty($allCdl) && is_array($allCdl)): ?>

    <?php foreach ($allCdl as $cdl): ?>
        <div class="card mb-3">
            <div class="card-header">
                <?= esc($cdl->id_cdl) ?>
                <?= esc($cdl->nome) ?>
            </div>
            <div class="card-body d-flex flex-row justify-content-center align-items-center">
                <span class="m-1"><?= esc($cdl->tipo) ?></span>
                <a href="/segreteria/cdl/edit?id=<?= esc($cdl->id_cdl) ?>" class="btn btn-primary m-1">Modifica</a>
                <a href="/segreteria/cdl/delete?id=<?= esc($cdl->id_cdl) ?>" class="btn btn-danger m-1">Elimina</a>
            </div>
        </div>

    <?php endforeach ?>

<?php else: ?>

    <h3>Nessun corso di laurea</h3>

<?php endif ?>