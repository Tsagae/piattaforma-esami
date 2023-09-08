<?php

namespace App\Controllers\Studenti;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\EsamiData;
use App\Repositories\SegretariData;
use Exception;

class GestioneEsami extends BaseController
{

    public function listNotIscrittoEsami()
    {
        $error = "";
        $data['esami'] = EsamiData::getEsamiNotIscritto(session()->get('studente')->matricola, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . "<h1>$error</h1>"
                . view('templates/footer');
        }
        $items = [];
        foreach ($data['esami'] as $esame) {
            $item = new \stdClass();
            $item->head = $esame->id_esame . " " . $esame->nome_insegnamento;
            $item->body = ["$esame->data $esame->nome_docente $esame->cognome_docente"];
            $item->buttons = [
                (object) [
                    "link" => "/studenti/esami/iscriviti?id=$esame->id_esame",
                    "style" => "btn btn-primary m-1",
                    "text" => "Iscriviti"
                ],
            ];
            $items[] = $item;
        }

        $data['items'] = $items;
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Esami</h1>"
            . view("templates/list", $data)
            . view('templates/footer');
    }

    public function listIscrittoEsami()
    {
        $error = "";
        $data['esami'] = EsamiData::getEsamiIscritto(session()->get('studente')->matricola, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . "<h1>$error</h1>"
                . view('templates/footer');
        }
        $items = [];
        foreach ($data['esami'] as $esame) {
            $item = new \stdClass();
            $item->head = $esame->id_esame . " " . $esame->nome_insegnamento;
            $item->body = ["$esame->data $esame->nome_docente $esame->cognome_docente"];
            $item->buttons = [
                (object) [
                    "link" => "/studenti/esami/cancella?id=$esame->id_esame",
                    "style" => "btn btn-primary m-1",
                    "text" => "Cancella Iscrizione"
                ],
            ];
            $items[] = $item;
        }

        $data['items'] = $items;
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Esami</h1>"
            . view("templates/list", $data)
            . view('templates/footer');
    }


    public function iscriviti()
    {
        $error = "";
        if (!$this->request->is('post')) {
            $esame = EsamiData::getEsame($this->request->getGet('id'), $error);

            return view('templates/header', ['title' => 'Studebnti'])
                . view('templates/confirmation', [
                    'submitValue' => "$esame->id_esame",
                    'text' => "Iscriviti $esame->nome_insegnamento $esame->nome_docente $esame->cognome_docente $esame->id_cdl",
                    'confirmText' => 'Conferma',
                    'cancelRedirect' => '/studenti',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        //$postString = var_export($post, true);
        //error_log("post: $postString");
        EsamiData::iscriviStudenteAEsame(session()->get("studente")->matricola, $post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studebnti'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Studebnti'])
            . '<h3>Studente iscritto correttamente</h3>'
            . view('templates/footer');
    }

}