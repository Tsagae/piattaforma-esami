<div class="d-flex flex-row justify-content-center align-items-center">
    <h1>Segretari</h1> <a href="/segreteria/segretari/add" class="btn btn-primary m-1">+</a>
</div>
<?php if (!empty($segretari) && is_array($segretari)): ?>

    <?php foreach ($segretari as $segretario): ?>
        <div class="card mb-3"">
        <div class="card-header">
            <p>
                <?= esc($segretario->nome) ?> <?= esc($segretario->cognome) ?> <?= esc($segretario->email) ?>
            </p>
        </div>
        <div class="card-body d-flex flex-row justify-content-center align-items-center">
            <a href="/segreteria/segretari/edit?id=<?= esc($segretario->id_segreteria) ?>"
               class="btn btn-primary">Modifica</a>
            <a href="/segreteria/segretari/delete?id=<?= esc($segretario->id_segreteria) ?>" class="btn btn-danger">Elimina</a>
        </div>
        </div>

    <?php endforeach ?>

<?php else: ?>

    <h3>Nessun segretario registrato</h3>

<?php endif ?>