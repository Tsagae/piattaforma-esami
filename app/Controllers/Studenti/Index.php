<?php

namespace App\Controllers\Studenti;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\EsamiData;
use App\Repositories\SegretariData;
use Exception;

class Index extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Esami</h1>"
            . view('studenti/index')
            . view('templates/footer');
    }

}