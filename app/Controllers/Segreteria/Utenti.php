<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;

class Utenti extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/utenti")
            . view('templates/footer');
    }
}