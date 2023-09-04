<?php

namespace App\Controllers;

use App\Database\PostgresConnection;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pages extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function view($page = 'test')
    {
        if (!is_file(APPPATH . 'Views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            throw new PageNotFoundException($page);
        }
        $dbConn = PostgresConnection::get();
        $dbRes = $dbConn->selectProcedure("db_esami.get_utenti");
        if ($dbRes !== false) {
            $data['users'] = $dbRes;
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        return view('templates/header', $data)
            . view('pages/' . $page)
            . view('templates/footer');
    }
}