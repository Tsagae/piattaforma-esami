Hello World!

<?php foreach ($users as $item): ?>
    <h5><?= esc($item->id_utente) . " " . esc($item->email) . " " . esc($item->password) . " " . esc($item->nome) . " " . esc($item->cognome) . " " . esc($item->ruolo) ?></h5>
<?php endforeach ?>
