<?php

namespace App\Controllers\Docenti;


use App\Controllers\BaseController;
use App\Repositories\EsamiData;
use App\Repositories\InsegnamentiData;

class Index extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Docenti'])
            . view('docenti/index')
            . view('templates/footer');
    }

    public function listInsegnamenti()
    {
        error_log("session: " . var_export(session()->get(), true));

        $error1 = "";
        $error2 = "";
        $data['noRecordsText'] = "Nessun insegnamento";
        $insegnamenti = InsegnamentiData::getInsegnamentiByIdDocente(session()->get('docente')->id_docente, $error1);
        $esami = EsamiData::getEsamiByIdDocente(session()->get('docente')->id_docente, $error2);

        if (!empty($error1)) {
            return view('templates/header', ['title' => 'Docenti'])
                . "$error1"
                . view('templates/footer');
        }
        if (!empty($error2)) {
            return view('templates/header', ['title' => 'Docenti'])
                . "<h1>Insegnamenti</h1>"
                . "$error2"
                . view('templates/footer');
        }

        $itemsInsegnamenti = [];
        foreach ($insegnamenti as $insegnamento) {
            $item = new \stdClass();
            $item->head = $insegnamento->id_insegnamento . " " . $insegnamento->nome;
            $item->body = ["$insegnamento->semestre Semestre", "$insegnamento->id_cdl"];
            $item->buttons = [
                (object) [
                    "link" => "/docenti/esami/add?id=$insegnamento->id_insegnamento",
                    "style" => "btn btn-primary m-1",
                    "text" => "Aggiungi esame"
                ],
            ];
            $itemsInsegnamenti[] = $item;
        }


        $itemsEsami = [];
        foreach ($esami as $esame) {
            $item = new \stdClass();
            $item->head = $esame->data . " " . $esame->nome;
            $item->body = ["$esame->semestre Semestre", "$esame->id_cdl"];
            $item->buttons = [
                (object) [
                    "link" => "/docenti/esami/edit?id=$esame->id_esame",
                    "style" => "btn btn-primary m-1",
                    "text" => "Modifica"
                ],
                (object) [
                    "link" => "/docenti/esami/delete?id=$esame->id_esame",
                    "style" => "btn btn-danger m-1",
                    "text" => "Elimina"
                ],
            ];
            $itemsEsami[] = $item;
        }


        $data['items'] = $itemsInsegnamenti;
        $dataEsami['items'] = $itemsEsami;
        error_log("items: " . var_export($itemsInsegnamenti, true));
        return view('templates/header', ['title' => 'Docenti'])
            . "<h1>Insegnamenti</h1>"
            . view("templates/list", $data)
            . "<h1>Esami</h1>"
            . view("templates/list", $dataEsami)
            . view('templates/footer');

    }
}