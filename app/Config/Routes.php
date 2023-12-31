<?php

namespace Config;

// Create a new instance of our RouteCollection class.
use App\Controllers\Docenti\GestioneEsami;
use App\Controllers\LoginController;
use App\Controllers\Pages;
use App\Controllers\SecondController;
use App\Controllers\Segreteria\Archivio;
use App\Controllers\Segreteria\Cdl;
use App\Controllers\Segreteria\Docenti;
use App\Controllers\Segreteria\Segretari;
use App\Controllers\Segreteria\Studenti;
use App\Controllers\Segreteria\Utenti;
use App\Controllers\Segreteria\Insegnamenti;
use App\Controllers\Studenti\Index;

$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('logout', [LoginController::class, 'logout']);
//$routes->match(['get', 'post'], 'secondtest/create', [SecondController::class, 'create']);
$routes->match(['get', 'post'], 'login', [LoginController::class, 'login']);
$routes->get( '/unauthorized', [LoginController::class, 'unauthorized']);

//Profilo
$routes->get('/profilo', [\App\Controllers\UserController::class, 'profileGet']);
$routes->post('/profilo', [\App\Controllers\UserController::class, 'profilePost']);

//Segreteria
$routes->get('/segreteria', [\App\Controllers\Segreteria\Index::class, 'index']);
$routes->get('/segreteria/utenti', [Utenti::class, 'index']);
//Segreteria/Studenti
$routes->get('/segreteria/studenti', [Studenti::class, 'listStudenti']);
$routes->match(['get', 'post'], '/segreteria/studenti/add', [Studenti::class, 'add']);
$routes->match(['get', 'post'], '/segreteria/studenti/delete', [Studenti::class, 'delete']);
$routes->match(['get', 'post'], '/segreteria/studenti/edit', [Studenti::class, 'edit']);
//Segreteria/Segretari
$routes->get('/segreteria/segretari', [Segretari::class, 'listSegretari']);
$routes->match(['get', 'post'], '/segreteria/segretari/add', [Segretari::class, 'add']);
$routes->match(['get', 'post'], '/segreteria/segretari/delete', [Segretari::class, 'delete']);
$routes->match(['get', 'post'], '/segreteria/segretari/edit', [Segretari::class, 'edit']);
//Segreteria/Docenti
$routes->get('/segreteria/docenti', [Docenti::class, 'listDocenti']);
$routes->match(['get', 'post'], '/segreteria/docenti/add', [Docenti::class, 'add']);
$routes->match(['get', 'post'], '/segreteria/docenti/delete', [Docenti::class, 'delete']);
$routes->match(['get', 'post'], '/segreteria/docenti/edit', [Docenti::class, 'edit']);
//Segreteria/cdl
$routes->get('/segreteria/cdl', [Cdl::class, 'listCdl']);
$routes->match(['get', 'post'], '/segreteria/cdl/add', [Cdl::class, 'add']);
$routes->match(['get', 'post'], '/segreteria/cdl/delete', [Cdl::class, 'delete']);
$routes->match(['get', 'post'], '/segreteria/cdl/edit', [Cdl::class, 'edit']);
//Segreteria/insegnamenti
$routes->match(['get', 'post'], '/segreteria/insegnamenti', [Insegnamenti::class, 'listInsegnamenti']);
$routes->match(['get', 'post'], '/segreteria/insegnamenti/add', [Insegnamenti::class, 'add']);
$routes->match(['get', 'post'], '/segreteria/insegnamenti/edit', [Insegnamenti::class, 'edit']);
$routes->match(['get', 'post'], '/segreteria/insegnamenti/delete', [Insegnamenti::class, 'delete']);
$routes->match(['get', 'post'], 'segreteria/insegnamenti/addpropedeutico', [Insegnamenti::class, 'addPropedeutico']);
$routes->match(['get', 'post'], 'segreteria/insegnamenti/deletepropedeutico', [Insegnamenti::class, 'deletePropedeutico']);
//Segreteria/archivio
$routes->get('/segreteria/archivio', [Archivio::class, 'listStudentiArchiviati']);
$routes->get('/segreteria/archivio/carriera', [Archivio::class, 'listCarrieraArchiviata']);
$routes->get('/segreteria/archivio/carrieravalida', [Archivio::class, 'listCarrieraValida']);



//Docenti
$routes->get('/docenti', [\App\Controllers\Docenti\Index::class, 'index']);
$routes->get('/docenti/insegnamenti', [\App\Controllers\Docenti\Index::class, 'listInsegnamenti']);
$routes->get('/docenti/esamipassati', [\App\Controllers\Docenti\Index::class, 'listEsamiPassati']);
$routes->get('/docenti/prossimiesami', [\App\Controllers\Docenti\Index::class, 'listProssimiEsami']);
//Docenti/Esami
$routes->get('/docenti/esami/add', [GestioneEsami::class, 'addGet']);
$routes->post('/docenti/esami/add', [GestioneEsami::class, 'addPost']);
$routes->get('/docenti/esami/delete', [GestioneEsami::class, 'deleteGet']);
$routes->post('/docenti/esami/delete', [GestioneEsami::class, 'deletePost']);
$routes->get('/docenti/esami/edit', [GestioneEsami::class, 'editGet']);
$routes->post('/docenti/esami/edit', [GestioneEsami::class, 'editPost']);
//Docenti/Esami/Valutazioni
$routes->get('/docenti/esami/valutazioni', [GestioneEsami::class, 'listValutazioni']);
$routes->get('/docenti/esami/valutazioni/valuta', [GestioneEsami::class, 'valutaGet']);
$routes->post('/docenti/esami/valutazioni/valuta', [GestioneEsami::class, 'valutaPost']);



//Studenti
$routes->get('/studenti', [\App\Controllers\Studenti\Index::class, 'index']);
//Studenti/Cdl
$routes->get('/studenti/cdl', [\App\Controllers\Studenti\Index::class, 'cdlList']);
//Studenti/Esami
$routes->get('/studenti/esami/prossimiesami', [\App\Controllers\Studenti\GestioneEsami::class, 'listNotIscrittoEsami']);
$routes->get('/studenti/esami/iscrizioni', [\App\Controllers\Studenti\GestioneEsami::class, 'listIscrittoEsami']);
$routes->match(['get', 'post'], '/studenti/esami/iscriviti', [\App\Controllers\Studenti\GestioneEsami::class, 'iscriviti']);
$routes->match(['get', 'post'], '/studenti/esami/cancella', [\App\Controllers\Studenti\GestioneEsami::class, 'deleteIscrizione']);
//Studenti/Carriera
$routes->get('/studenti/carriera', [\App\Controllers\Studenti\Carriera::class, 'listCarriera']);
$routes->get('/studenti/carrieravalida', [\App\Controllers\Studenti\Carriera::class, 'listCarrieraValida']);
//Studenti/Rinuncia
$routes->match(['get', 'post'], '/studenti/rinuncia', [Index::class, 'rinuncia']);



//Testing
//$routes->get('pages', [Pages::class, 'index']);
//$routes->match(['get', 'post'], 'pages/(:segment)', [Pages::class, 'view']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}