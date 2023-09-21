<?php

namespace App\Controllers\Studenti;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\EsamiData;
use App\Repositories\HelperData;
use App\Repositories\InsegnamentiData;
use App\Repositories\SegretariData;
use App\Repositories\StudentiData;
use Exception;

class Index extends BaseController
{
    public function index(): string
    {
        return view('templates/header', ['title' => 'Studenti'])
            . view('templates/menu', [
                'title' => 'Studente',
                'items' => [
                    (object)[
                        "link" => "/studenti/esami/prossimiesami",
                        "text" => "Iscriviti a un Esame"
                    ],
                    (object)[
                        "link" => "/studenti/esami/iscrizioni",
                        "text" => "Iscrizioni Confermate"
                    ],
                    (object)[
                        "link" => "/studenti/carriera",
                        "text" => "Visualizza Carriera"
                    ],
                    (object)[
                        "link" => "/studenti/carrieravalida",
                        "text" => "Visualizza Carriera Valida"
                    ],
                    (object)[
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
        $propedeutici = [];
        $get_cdl = $this->request->getGet('idcdl');
        $id_cdl = empty($get_cdl) ? session()->get('studente')->id_cdl : $get_cdl;
        $allCdl = CdlData::getAllCdl();
        $options = [];
        $firstOption = null;
        foreach ($allCdl as $cdl) {
            $option = new \stdClass();
            $option->value = $cdl->id_cdl;
            $option->text = "$cdl->nome $cdl->tipo $cdl->id_cdl";
            $options[] = $option;
            if ($option->value == $id_cdl) {
                $firstOption = $option;
            }
        }

        //Insegnamenti
        $insegnamenti = InsegnamentiData::get_insegnamenti_by_cdl($id_cdl, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studenti'])
                . "$error"
                . view('templates/footer');
        }
        $insegnamentiItems = [];
        foreach ($insegnamenti as $insegnamento) {
            $item = new \stdClass();
            $tempPropedeutici = InsegnamentiData::getPropedeuticiByIdInsegnamento($insegnamento->id_insegnamento, $error);
            if (count($tempPropedeutici) > 0) {
                $propedeutici[] = (object)['insegnamento' => $insegnamento->nome, 'propedeutici' => $tempPropedeutici];
            }
            $item->head = $insegnamento->nome;
            $item->body = ["Anno: $insegnamento->anno ", "Semestre: $insegnamento->semestre", "Docente: $insegnamento->docente_nome $insegnamento->docente_cognome"];
            $item->buttons = [];
            $insegnamentiItems[] = $item;

        }

        $id_cdl = esc($id_cdl);

        //Propedeuticità
        $propedeuticiItems = [];
        foreach ($propedeutici as $propedeutico) {
            $item = new \stdClass();
            $item->head = $propedeutico->insegnamento;
            $item->body = ["Propedeutici: "];
            foreach ($propedeutico->propedeutici as $richiesto) {
                $item->body[] = "$richiesto->nome_insegnamento";
            }
            $item->buttons = [];
            $propedeuticiItems[] = $item;
        }


        return view('templates/header', ['title' => 'Studenti'])
            . "<h1>Insegnamenti $id_cdl</h1>"
            . view("templates/selection", ['options' => $options, 'firstOption' => $firstOption])
            . view("templates/list", ['items' => $insegnamentiItems, 'noRecordsText' => "Nessun insegnamento presente per il corso di laurea $id_cdl"])
            . "<h1>Propedeuticità</h1>"
            . view("templates/list", ['items' => $propedeuticiItems, 'noRecordsText' => "Nessuna propedeuticità presente per il corso di laurea $id_cdl"])
            . view('templates/footer');

    }

    public function rinuncia()
    {
        $studente = session()->get("studente");
        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Studenti'])
                . view('templates/confirmation', [
                    'submitValue' => "$studente->matricola",
                    'text' => "Sciuro di voler rinunciare agli studi?",
                    'confirmText' => 'Conferma',
                    'cancelRedirect' => '/profilo',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $error = "";
        StudentiData::deleteStudente($post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Studente'])
                . esc($error)
                . view('templates/footer');
        }
        session()->destroy();
        return view('templates/header', ['title' => 'Studente'])
            . '<h3>Rinuncia agli studi effettuata con successo</h3>'
            . view('templates/redirect', ['url' => "/", 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

}