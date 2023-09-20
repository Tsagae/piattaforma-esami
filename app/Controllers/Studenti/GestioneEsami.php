<?php

namespace App\Controllers\Studenti;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\EsamiData;
use App\Repositories\HelperData;
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
            $disabledBtn = $esame->propedeutici_mancanti != 0 ? " disabled" : "";
            $item->buttons = [
                (object)[
                    "link" => "/studenti/esami/iscriviti?id=$esame->id_esame",
                    "style" => "btn btn-primary m-1" . $disabledBtn,
                    "text" => "Iscriviti"
                ],
            ];
            $items[] = $item;
        }

        $data['items'] = $items;
        $data['noRecordsText'] = "Non ci sono esami disponibili per l'iscrizione";
        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Esami</h1>"
            . view("templates/list", $data)
            . view('templates/footer');
    }

    public function listIscrittoEsami()
    {

        $error = "";
        $data['esami'] = EsamiData::getNextEsamiIscritto(session()->get('studente')->matricola, $error);
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
            $item->buttons = [];
            if ($esame->data > HelperData::getTodayDate()) {
                $item->buttons = [
                    (object)[
                        "link" => "/studenti/esami/cancella?id=$esame->id_esame",
                        "style" => "btn btn-danger m-1",
                        "text" => "Cancella Iscrizione"
                    ],
                ];
            }
            $items[] = $item;
        }

        $data['items'] = $items;
        $data['noRecordsText'] = "Nessuna iscrizione attiva";
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

            return view('templates/header', ['title' => 'Studenti'])
                . view('templates/confirmation', [
                    'submitValue' => "$esame->id_esame",
                    'text' => "Iscriviti $esame->nome_insegnamento $esame->nome_docente $esame->cognome_docente $esame->id_cdl $esame->data",
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
            return view('templates/header', ['title' => 'Studenti'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Studenti'])
            . '<h3>Studente iscritto correttamente</h3>'
            . view('templates/redirect', ['url' => '/studenti', 'delay' => 3])
            . view('templates/footer');
    }

    public function deleteIscrizione()
    {
        $error = "";
        if (!$this->request->is('post')) {
            $esame = EsamiData::getEsame($this->request->getGet('id'), $error);

            return view('templates/header', ['title' => 'Studenti'])
                . view('templates/confirmation', [
                    'submitValue' => "$esame->id_esame",
                    'text' => "Cancella Iscrizione $esame->nome_insegnamento $esame->nome_docente $esame->cognome_docente $esame->id_cdl $esame->data",
                    'confirmText' => 'Cancella',
                    'cancelRedirect' => '/studenti',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        //$postString = var_export($post, true);
        //error_log("post: $postString");
        EsamiData::deleteIscrizioneEsame(session()->get("studente")->matricola, $post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Studenti'])
            . '<h3>Iscrizione rimossa correttamente</h3>'
            . view('templates/redirect', ['url' => '/studenti', 'delay' => 3])
            . view('templates/footer');
    }

}