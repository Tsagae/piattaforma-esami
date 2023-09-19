<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Docenti'])
            . view('templates/menu', [
                'title' => 'Segretario',
                'items' => [
                    (object) [
                        "link" => "/segreteria/utenti",
                        "text" => "Gesitone Utenti"
                    ],
                    (object) [
                        "link" => "/segreteria/cdl",
                        "text" => "Gestione CDL"
                    ],
                    (object) [
                        "link" => "/segreteria/insegnamenti",
                        "text" => "Gestione Insegnamenti"
                    ],
                    (object) [
                        "link" => "/segreteria/archivio",
                        "text" => "Visualizza Archivio"
                    ],
                ]
            ])
            . view('templates/footer');
    }
}