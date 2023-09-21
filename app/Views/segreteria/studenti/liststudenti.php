<div class="d-flex flex-row justify-content-center align-items-center">
    <h1>Studenti</h1> <a href="/segreteria/studenti/add" class="btn btn-primary m-1">+</a>
</div>
<?php if (!empty($studenti) && is_array($studenti)): ?>

    <?php foreach ($studenti as $studente): ?>
        <div class="card mb-3"">
        <div class="card-header">
            <p>
                Matricola: <?= esc($studente->matricola) ?> <?= esc($studente->nome) ?> <?= esc($studente->cognome) ?>
            </p>
        </div>
        <div class="card-body d-flex flex-row justify-content-center align-items-center">
            <div class="card-text me-2"><?= esc($studente->id_cdl) ?> <?= esc($studente->nomecdl) ?> <?= esc($studente->tipo) ?></div>
            <a href="/segreteria/studenti/edit?id=<?= esc($studente->matricola) ?>"
               class="btn btn-primary">Modifica</a>
            <a href="/segreteria/studenti/delete?id=<?= esc($studente->matricola) ?>" class="btn btn-danger">Elimina</a>
        </div>
        </div>

    <?php endforeach ?>

<?php else: ?>

    <h3>Nessuno studente registrato</h3>

<?php endif ?>