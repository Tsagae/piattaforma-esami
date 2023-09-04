<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Segreteria'])
            . view('segreteria/index')
            . view('templates/footer');
    }
}