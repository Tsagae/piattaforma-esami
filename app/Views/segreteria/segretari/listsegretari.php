<div>
    <h1>Segretari</h1>
</div>
<?php if (!empty($segretari) && is_array($segretari)): ?>

    <?php foreach ($segretari as $segretario): ?>
        <div class="card mb-3"">
            <div class="card-header">
                <p>
                    <?= esc($segretario->id_segreteria) ?> <?= esc($segretario->nome) ?> <?= esc($segretario->cognome) ?> <?= esc($segretario->email) ?>
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