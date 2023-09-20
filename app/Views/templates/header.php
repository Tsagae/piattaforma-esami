<?php
function navBar(?object $user): string
{
    $barEnding = '<a class="navbar-brand" href="/profilo">Profilo</a><a class="navbar-brand" href="/logout">Logout</a>';
    $innerBar = "";
    switch ($user->ruolo ?? null) {
        case "segreteria":
            $innerBar = <<<HTML
            <li class="nav-item">
                <a class="nav-link" href="/segreteria/utenti">Gestione Utenti</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/segreteria/cdl">Gestione CDL</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/segreteria/insegnamenti">Gestione Insegnamenti</a>
            </li>
        HTML;
            break;
        case "studente":
            $innerBar = <<<HTML
            <li class="nav-item">
                <a class="nav-link" href="/studenti/esami/prossimiesami">Iscriviti a un Esame</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/studenti/esami/iscrizioni">Iscrizioni Confermate</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/studenti/carriera">Visualizza Carriera</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/studenti/carrieravalida">Visualizza Carriera Valida</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/studenti/cdl">Visualizza CDL</a>
            </li>
        HTML;
            break;
        case "docente":
            $innerBar = <<<HTML
            <li class="nav-item">
                <a class="nav-link" href="/docenti/insegnamenti">Insegnamenti</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/docenti/prossimiesami">Prossimi Esami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/docenti/esamipassati">Esami Passati</a>
            </li>
        HTML;
            break;
        default:
            $barEnding = '<a class="navbar-brand" href="/login">Login</a>';
    }

    return <<<HTML
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Unimips</a>
                <button class="navbar-toggler" typgine="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                       $innerBar
                    </ul>
                </div>
            </div>
            <span class="navbar-text">
                $barEnding
            </span>
        </nav>
    HTML;
}

?>

<!doctype html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <title>
        <?= esc($title) ?>
    </title>
</head>

<body>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
<header>
    <?= navBar(session()->get('user')) ?>
</header>
<div class="container d-flex flex-column align-items-center justify-content-center">