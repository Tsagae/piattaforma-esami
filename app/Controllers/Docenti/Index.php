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
            . view('templates/menu', [
                'title' => 'Docente',
                'items' => [
                    (object) [
                        "link" => "/docenti/insegnamenti",
                        "text" => "Insegnamenti"
                    ],
                    (object) [
                        "link" => "/docenti/prossimiesami",
                        "text" => "Prossimi Esami"
                    ],
                    (object) [
                        "link" => "/docenti/esamipassati",
                        "text" => "Esami Passati"
                    ],
                ]
            ])
            . view('templates/footer');
    }

    public function listInsegnamenti()
    {
        $error1 = "";
        $data['noRecordsText'] = "Nessun insegnamento";
        $insegnamenti = InsegnamentiData::getInsegnamentiByIdDocente(session()->get('docente')->id_docente, $error1);

        if (!empty($error1)) {
            return view('templates/header', ['title' => 'Docenti'])
                . "$error1"
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

        $data['items'] = $itemsInsegnamenti;
        return view('templates/header', ['title' => 'Docenti'])
            . "<h1>Insegnamenti</h1>"
            . view("templates/list", $data)
            . view('templates/footer');

    }

    public function listProssimiEsami()
    {
        $error = "";
        $esami = EsamiData::getEsamiFuturiByIdDocente(session()->get('docente')->id_docente, $error);
        return $this->getEsami($esami, $error, "Prossimi Esami", 0);

    }

    public function listEsamiPassati()
    {
        $error = "";
        $esami = EsamiData::getEsamiPassatiByIdDocente(session()->get('docente')->id_docente, $error);
        return $this->getEsami($esami, $error, "Esami Passati", 1);
    }


    private function getEsami(array $esami, string $error, string $title, int $btnOptions = -1): string
    {
        $dataEsami['noRecordsText'] = "Nessun esame";

        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . "$error"
                . view('templates/footer');
        }

        $itemsEsami = [];
        foreach ($esami as $esame) {
            $item = new \stdClass();
            $item->head = $esame->data . " " . $esame->nome;
            $item->body = ["$esame->semestre Semestre", "$esame->id_cdl"];
            $item->buttons = [];
            switch ($btnOptions) {
                case 0:
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
                    break;
                case 1:
                    $item->buttons = [
                        (object) [
                            "link" => "/docenti/esami/valutazioni?id=$esame->id_esame",
                            "style" => "btn btn-primary m-1",
                            "text" => "Valuatazioni"
                        ],
                    ];
                    break;
            }

            $itemsEsami[] = $item;
        }


        $dataEsami['items'] = $itemsEsami;
        $title = esc($title);
        return view('templates/header', ['title' => 'Docenti'])
            . "<h1>$title</h1>"
            . view("templates/list", $dataEsami)
            . view('templates/footer');

    }
}