# Documentazione ten100%cica

## Indice

- [Requisiti e installazione](#requisiti-e-installazione)
- [Schema ER](#schema-er)
- [Schema Logico](#schema-logico)
- [Funzionalità](#funzionalità)
- [Struttura del progetto](#struttura-del-progetto)
- [Prove di funzionamento](#prove-di-funzionamento)

## Requisiti e installazione

I requisiti e istruzioni per l'installazione e deploy possono essere trovati nel [manuale utente](./README.md)

## Schema ER

![ER](./ER.png)

## Schema Logico

![Logico](./logico.png)

## Funzionalità

### Generali

- Login
- Logout
- Visualizzazione Profilo
    - Modifica Password
    - Rinuncia agli studi (solo se studente e con ancora esami mancanti)

### Segreteria

- Gesione Utenti (tutti gli utenti vengono creati con una password di default che può essere cambiata in ogni momento
  dalla segreteria e dall'utente stesso)
    - Studenti
        - Aggiunta
        - Modifica
        - Rimozione (trasferisce lo studente e le sue valutazioni nell'archivio. Se ha ancora esami mancanti risulta
          come rinuncia agli studi mentre se ha completato tutti gli esami del suo cdl risulta come laureato)
    - Docenti
        - Aggiunta
        - Modifica
        - Rimozione
    - Segretetari
        - Aggiunta
        - Modifica
        - Rimozione
- Gestione CDL
    - Aggiunta
    - Modifica (nome e tipo di laurea)
    - Rimozione
- Gesione Insegnamenti
    - Aggiunta
    - Modifica
        - Modifica delle propedeuticità (Aggiunta e rimozione)
    - Rimozione
    - Visualizza Archivio (Visualizza tutti gli studenti che hanno rinunciato agli studi o che hanno completato il loro
      cdl)
        - Mostra carriera
        - Mostra carriera valida

### Docente

- Insegnamenti
    - Aggiungi esame (selezione della data)
- Prossimi esami
    - Modifica (cambio data)
    - Elimina
- Esami passati
    - Visualizza valutazioni
    - Modifica valutazioni

### Studente

- Iscrizone agli esami (è possibile iscriversi a tutti gli esami al massimo del giorno seguente e solamente se sono
  rispettate le propedeuticità, altrimenti il pulsante di iscrizione è disabilitato)
- Iscrizioni confermate (gli esami a cui è ancora possibile iscriversi sono gli stessi da cui è possibile disiscriversi)
    - Cancella iscrizione
- Visualizza carriera
- Visualizza carriera valida
- Visualizza CDL (visualizza tutti gli insegnamenti di ogni corso di laurea e le loro propedeuticità)

## Struttura del progetto

Il progetto è stato sviluppato con il framework [CodeIgniter 4](https://codeigniter.com/) e
utilizza [Composer](https://getcomposer.org/) come gestore delle dipendenze.
Il framework è stato scelto per la sua semplicità e per rimanere vicino allo sviluppo di un progetto "from scratch".
Non sono state utilizzate librerie esterne per la gestione del database, ma solamente il driver nativo di php per
PostgreSQL.
Delle funzioni offerte dal framework sono state utilizzate:

- Gesione automatica dell'import dei file dato il namespace
- Routing e pattern MVC (In questo caso Repository-View-Controller)
- Form data validation ed escaping delle string
- Templating tramite views
- Redirection su mancata/errata autenticazione

### Struttura dei file

Tutta l'applicazione è contenuta nella cartella [app](./app) che contiene i seguenti file e cartelle:

- [app](./app)
    - [Config](./app/Config)
        - [Filters.php](./app/Config/Filters.php) Configurazione dei filtri
        - [Routes.php](./app/Config/Routes.php) Configurazione delle routes
    - [Controllers](./app/Controllers)
    - [Database](./app/Database)
        - [PostgresConnection.php](./app/Database/PostgresConnection.php) Classe per la gestione della connessione al
          database e per l'esecuzione delle query
    - [Filters](./app/Filters)
        - [AuthDocentiFilter.php](./app/Filters/AuthDocentiFilter.php) Filtro per l'autenticazione del docente
        - [AuthFilter.php](./app/Filters/AuthFilter.php) Filtro per l'autenticazione generica
        - [AuthSegreteriaFilter.php](./app/Filters/AuthSegreteriaFilter.php) Filtro per l'autenticazione della
          segreteria
        - [AuthStudentiFilter.php](./app/Filters/AuthStudentiFilter.php) Filtro per l'autenticazione dello studente
    - [Repositories](./app/Repositories) Le repositories classi che astraggono lo scambio di dati con il database per
      ogni entità. Utilizzano la classe [PostgresConnection.php](./app/Database/PostgresConnection.php) per eseguire le
      query
    - [Views](./app/Views) Le views sono il sistema di templating utilizzato da
      CodeIgniter ([documentazione views](#views))

### Views

Le views sono il sistema di templating utilizzato da CodeIgniter.
Sono file php che usano la sintassi standard del linguaggio e vengono utilizzate per generare pagine html.
Le view differeiscono dal php tradizionale solamente per due meccanismi:

- Le variabili non inizializzate saranno inizializzate dal controller che richiama la view
- Delle funzioni di supporto come ``esc()`` per l'escaping delle string

Le views sono state utilizzate per il render delle pagine e per la generazione di componenti riutilizzabili come il
componente [menu](./app/Views/templates/menu.php) illustrato di seguito:

```php
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
```

Sarà compito del controller che richiama la view inizializzare la variabile ``$title`` e la lista di
oggetti ``$items`` (che dovranno avere come proprietà ``link`` e ``text``) che verrà poi utilizzata per generare gli
elementi del menu

### Controllers

I controller sono le classi che vengono istanziate e richiamate per gestire le richieste. I controller ereditano
tutti [BaseController](./app/Controllers/BaseController.php) che fornisce alcune funzionalità di base come
l'oggetto ``$request`` per accedere ai dati della richiesta http e le funzioni per gestire le view

[Esempio di controller](./app/Controllers/Studenti/Index.php)):

```php
class Index extends BaseController
{
    public function index(): string
    {
        return view('templates/header', ['title' => 'Studenti'])
            . view('templates/menu', [
                'title' => 'Studente',
                'items' => [
                    (object)[
                        "link" => "/studenti/esami/prossimiesami",
                        "text" => "Iscriviti a un Esame"
                    ],
                    (object)[
                        "link" => "/studenti/esami/iscrizioni",
                        "text" => "Iscrizioni Confermate"
                    ],
                    (object)[
                        "link" => "/studenti/carriera",
                        "text" => "Visualizza Carriera"
                    ],
                    (object)[
                        "link" => "/studenti/carrieravalida",
                        "text" => "Visualizza Carriera Valida"
                    ],
                    (object)[
                        "link" => "/studenti/cdl",
                        "text" => "Visualizza CDL"
                    ],
                ]
            ])
            . view('templates/footer');
    }
}
```

Questo controller esegue il render della pagina principale dello studente che combina tre
view ([header](./app/Views/templates/header.php), [menu](./app/Views/templates/menu.php)
e [footer](./app/Views/templates/footer.php). La funzione ``view()`` restituisce la view renderizzata come string
facilitandone la concatenzaione) utilizzando la view [menu illustrata in precendeza](#views)

### Routing

Tutti gli endpoint sono specificati nel file [Routes.php](./app/Config/Routes.php)
Esempio di routing:

```php
$routes->get('/studenti', [\App\Controllers\Studenti\Index::class, 'index']);
```

Questo routing specifica che la richiesta GET all'endpoint ``/studenti`` deve essere gestita dalla classe
``\App\Controllers\Studenti\Index`` con il metodo ``index()``

Esempio di controller che accetta richieste GET e POST:

```php
$routes->match(['get', 'post'], 'login', [LoginController::class, 'login']);
```

Il controller conosce il tipo di richiesta tramite la proprietà ``$request`` della classe ``Controller``:

```php
$this->request->is('post')
```

### Interazione con il database

L'interazione con il database nei controller viene fatta solamente dalle repositories

Le repository interrogano il database tramite la classe [PostgresConnection](./app/Database/PostgresConnection.php)
La classe viene sempre instanziata con il metodo ```PostgresConnection::Get()``` e le procedure vengono richiamate con
due funzioni variadiche ```PostgresConnection::callProcedure()``` e ```PostgresConnection::selectProcedure()```

Internamente la classe gestisce le query con il seguente metodo:

```php
private function query_params(string $query, array $argArr): bool|array
{
    //Costruzione della stringa di placeholder per la query parametrizzata
    $procArgs = '(';
    for ($i = 0; $i < count($argArr); $i++) {
        $sArg = $i + 1;
        $procArgs .= "\$$sArg, ";
    }
    $procArgs = rtrim($procArgs);
    $procArgs = rtrim($procArgs, ',');
    $procArgs .= ')';
    //Connessione al database ed esecuzione della query
    $conn = $this->connect();
    $dbRes = pg_query_params($conn, "$query$procArgs;", $argArr);
    if ($dbRes === false)
        return false;
    $resData = array();
    while ($dbData = pg_fetch_object($dbRes)) {
        $resData[] = $dbData;
    }
    return $resData;
}
```

Il metodo esegue una query parametrizzata e provvede al fetch dei risultati restituendoli sotto forma di array