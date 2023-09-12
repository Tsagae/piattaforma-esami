<?php

namespace App\Controllers\Studenti;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\EsamiData;
use App\Repositories\InsegnamentiData;
use App\Repositories\SegretariData;
use Exception;

class Index extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Studenti'])
            . view('templates/menu', [
                'title' => 'Studente',
                'items' => [
                    (object) [
                        "link" => "/studenti/esami/prossimiesami",
                        "text" => "Iscriviti a un Esame"
                    ],
                    (object) [
                        "link" => "/studenti/esami/iscrizioni",
                        "text" => "Iscrizioni Confermate"
                    ],
                    (object) [
                        "link" => "/studenti/carriera",
                        "text" => "Visualizza Carriera"
                    ],
                    (object) [
                        "link" => "/studenti/carrieravalida",
                        "text" => "Visualizza Carriera Valida"
                    ],
                    (object) [
                        "link" => "/studenti/cdl",
                        "text" => "Visualizza CDL"
                    ],
                ]
            ])
            . view('templates/footer');
    }

    public function cdlList(): string
    {
        $error = "";
        $id_cdl = session()->get('studente')->id_cdl;
        $data['insegnamenti'] = InsegnamentiData::get_insegnamenti_by_cdl($id_cdl, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . "$error"
                . view('templates/footer');
        }
        $items = [];
        foreach ($data['insegnamenti'] as $insegnamento) {
            $item = new \stdClass();
            $item->head = $insegnamento->nome;
            $item->body = ["Anno: $insegnamento->anno ", "Semestre: $insegnamento->semestre", "Docente: $insegnamento->docente_nome $insegnamento->docente_cognome"];
            $item->buttons = [];
            $items[] = $item;
        }

        $data['items'] = $items;
        $id_cdl = esc($id_cdl);
        $data['noRecordsText'] = "Nessun insegnamento presente per il corso di laurea $id_cdl";
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Insegnamenti $id_cdl</h1>"
            . view("templates/list", $data)
            . view('templates/footer');

    }

}