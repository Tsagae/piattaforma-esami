## Requisiti

### PHP
PHP 8 o superiore con le seguenti estensioni:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- json (enabled by default - don't turn it off)
- pgsql

### Composer 
https://getcomposer.org/doc/00-intro.md

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

