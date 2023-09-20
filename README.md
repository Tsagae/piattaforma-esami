## Setup

creare una copia del file env con nome .env con la seguente configurazione:
```
database.default.hostname = [hostname o indirizzo]
database.default.database = [nome del database]
database.default.username = [postgres username]
database.default.password = [postgres password]
```

composer install
php ./spark serve

## Server Requirements

PHP version 8 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- pgsql
