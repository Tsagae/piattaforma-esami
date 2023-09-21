<?php

namespace App\Controllers\Studenti;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\EsamiData;
use App\Repositories\SegretariData;
use Exception;

class Carriera extends BaseController
{

    public function listCarriera()
    {
        $error = "";
        $data['esami'] = EsamiData::getAllEsamiIscritto(session()->get('studente')->matricola, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . "<h1>$error</h1>"
                . view('templates/footer');
        }
        $items = [];
        foreach ($data['esami'] as $esame) {
            $item = new \stdClass();
            $item->head = $esame->nome_insegnamento;
            $item->body = ["$esame->data $esame->nome_docente $esame->cognome_docente"];
            if ($esame->voto != null) {
                $item->body[] = "Voto: $esame->voto";
                $item->body[] = "Data Verbalizzazione: $esame->data_verbalizzazione";
            }
            $item->buttons = [];
            $items[] = $item;
        }

        $data['items'] = $items;
        $data['noRecordsText'] = "Nessun esame registrato";
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Carriera</h1>"
            . view("templates/list", $data)
            . view('templates/footer');
    }

    public function listCarrieraValida()
    {
        $error = "";
        $data['esami'] = EsamiData::getCarrieraValida(session()->get('studente')->matricola, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . "<h1>$error</h1>"
                . view('templates/footer');
        }
        $items = [];
        foreach ($data['esami'] as $esame) {
            $item = new \stdClass();
            $item->head = $esame->nome_insegnamento;
            $item->body = ["$esame->data $esame->nome_docente $esame->cognome_docente"];
            if ($esame->voto != null) {
                $item->body[] = "Voto: $esame->voto";
                $item->body[] = "Data Verbalizzazione: $esame->data_verbalizzazione";
            }
            $item->buttons = [];
            $items[] = $item;
        }

        $data['items'] = $items;
        $data['noRecordsText'] = "Nessun esame valido in carriera";
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Carriera Valida</h1>"
            . view("templates/list", $data)
            . view('templates/footer');
    }

}