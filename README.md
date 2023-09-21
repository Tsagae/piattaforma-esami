# Manuale utente progetto di Basi di Dati laboratorio 2022/2023

Il software è stato sviluppato con il framework [CodeIgniter 4](https://codeigniter.com/).
Documentazione di riferimento: [CodeIgniter 4 User Guide](https://codeigniter.com/user_guide/index.html)

Inoltre è stato utilizzato [Composer](https://getcomposer.org/) come gestore delle dipedenze.

Il sito è stato sviluppato e testato su Linux

Realizzato da Matteo Zagheno (987403)

[Documentazione tenica](./doc.md)

## Requisiti

### PHP

PHP 8 o superiore con le seguenti estensioni:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- json (enabled by default - don't turn it off)
- pgsql

### Composer

[composer installation](https://getcomposer.org/doc/00-intro.md)

## Setup

Il database può essere popolato con [dump.sql](./dump.sql) che è stato generato
usando [pg_dump](https://www.postgresql.org/docs/current/app-pgdump.html)

Il dump è già popolato con i seguenti utenti per facilitare il testing (tutti gli utenti hanno come password "
password"):

| Email                               | Password | Ruolo      |
|-------------------------------------|----------|------------|
| matteo.zagheno@unimips.it           | password | Studente   |
| albertonunzio.borghese@unimips.it   | password | Docente    |
| segretario.segretario@unimips.it    | password | Segretario |
| mattia.delledonne@unimips.it        | password | Studente   |
| luca.favini@unimips.it              | password | Studente   |
| docente.fisica@unimips.it           | password | Docente    |
| alberto.ceselli@unimips.it          | password | Docente    |
| massimo.santini@unimips.it          | password | Docente    |
| valerio.bellandi@unimips.it         | password | Docente    |
| studentenome.studcognome@unimips.it | password | Studente   |

"password" è la password di default che viene assegnata a ogni utente al momento della creazione

Nella root directory del progetto:

- Creare una copia del file [env](./env) con nome .env con la seguente configurazione:

```
database.default.hostname = [hostname o indirizzo]
database.default.database = [nome del database]
database.default.username = [postgres username]
database.default.password = [postgres password]

CI_ENVIRONMENT = production
```

- Per avviare il server:

```
composer install
php ./spark serve
```