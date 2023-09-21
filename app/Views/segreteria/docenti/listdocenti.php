<div class="d-flex flex-row justify-content-center align-items-center">
    <h1>Docenti</h1> <a href="/segreteria/docenti/add" class="btn btn-primary m-1">+</a>
</div>
<?php if (!empty($docenti) && is_array($docenti)): ?>

    <?php foreach ($docenti as $docente): ?>
        <div class="card mb-3"">
        <div class="card-header">
            <p>
                <?= esc($docente->nome) ?> <?= esc($docente->cognome) ?> <?= esc($docente->email) ?>
            </p>
        </div>
        <div class="card-body d-flex flex-row justify-content-center align-items-center">
            <a href="/segreteria/docenti/edit?id=<?= esc($docente->id_docente) ?>"
               class="btn btn-primary">Modifica</a>
            <a href="/segreteria/docenti/delete?id=<?= esc($docente->id_docente) ?>" class="btn btn-danger">Elimina</a>
        </div>
        </div>

    <?php endforeach ?>

<?php else: ?>

    <h3>Nessun docente registrato</h3>

<?php endif ?>