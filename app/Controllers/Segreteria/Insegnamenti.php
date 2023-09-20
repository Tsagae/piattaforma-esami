<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\DocentiData;
use App\Repositories\InsegnamentiData;
use App\Repositories\CdlData;
use Exception;

class Insegnamenti extends BaseController
{
    public function listInsegnamenti()
    {
        helper('form');

        $data['noRecordsText'] = "Nessun insegnamento";
        $data['allCdl'] = CdlData::getAllCdl();
        if (!$this->request->is('post')) {
            $data['insegnamenti'] = InsegnamentiData::getInsegnamenti();
        } else {
            $post = $this->request->getPost(['id_cdl']);
            $data['insegnamenti'] = InsegnamentiData::getInsegnamentiByCdl($post['id_cdl']);
        }


        $items = [];
        foreach ($data['insegnamenti'] as $insegnamento) {
            $item = new \stdClass();
            $item->head = $insegnamento->id_insegnamento . " " . $insegnamento->nome;
            $item->body = ["$insegnamento->semestre Semestre", "$insegnamento->docente_nome $insegnamento->docente_cognome"];
            $item->buttons = [
                (object)[
                    "link" => "/segreteria/insegnamenti/edit?id=$insegnamento->id_insegnamento",
                    "style" => "btn btn-primary m-1",
                    "text" => "Modifica"
                ],
                (object)[
                    "link" => "/segreteria/insegnamenti/delete?id=$insegnamento->id_insegnamento",
                    "style" => "btn btn-danger m-1",
                    "text" => "Elimina"
                ],
            ];
            $items[] = $item;
        }


        $data['items'] = $items;
        error_log("items: " . var_export($items, true));
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/insegnamenti/listinsegnamenti", $data)
            . view("templates/list", $data)
            . view('templates/footer');
    }


    public function add()
    {
        helper('form');

        $data['allCdl'] = CdlData::getAllCdl();
        $data['docenti'] = DocentiData::getDocenti();

        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/add', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['semestre', 'nome', 'id_docente', 'id_cdl', 'anno']);
        error_log("post" . var_export($post, true));
        if (
            !$this->validateData($post, [
                'semestre' => 'required|max_length[1]|min_length[1]|regex_match[/[12]/]',
                'nome' => 'required|max_length[255]|min_length[2]',
                //TODO add more validation
                'id_docente' => 'required',
                'id_cdl' => 'required',
                'anno' => 'required',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/add', $data)
                . view('templates/footer');
        }

        $error = "";
        InsegnamentiData::addInsegnamento((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/add', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Insegnamento inserito correttamente</h1>'
            . view('templates/footer');
    }

    public function edit()
    {
        helper('form');

        $tempCdl = CdlData::getAllCdl();
        $tempDocenti = DocentiData::getDocenti();
        $docenti = [];
        foreach ($tempDocenti as $docente) {
            $docenti[$docente->id_docente] = $docente;
        }
        $allCdl = [];
        foreach ($tempCdl as $cdl) {
            $allCdl[$cdl->id_cdl] = $cdl;
        }

        $data['docenti'] = $docenti;
        $data['allCdl'] = $allCdl;
        //$data['docenti']
        $error = "";
        $propedeutici = InsegnamentiData::getPropedeuticiByIdInsegnamento($this->request->getGet('id'), $error);
        $data['propedeutici'] = $propedeutici;
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        if (!$this->request->is('post')) {
            $insegnamento = InsegnamentiData::getInsegnamento($this->request->getGet('id'), $error);
            $data['insegnamento'] = $insegnamento;
            if (!empty($error)) {
                $data['queryError'] = $error;
            }
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/edit', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['id_insegnamento', 'semestre', 'nome', 'id_docente', 'id_cdl', 'propedeuticita[]', 'anno']);
        error_log("post" . var_export($post, true));
        if (
            !$this->validateData($post, [
                'id_insegnamento' => 'required|max_length[255]|min_length[1]',
                'semestre' => 'required|max_length[1]|min_length[1]|regex_match[/[12]/]',
                'nome' => 'required|max_length[255]|min_length[2]',
                //TODO add more validation
                'id_docente' => 'required',
                'id_cdl' => 'required',
                'anno' => 'required',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/edit', $data)
                . view('templates/footer');
        }
        error_log("prop: " . var_export($post['propedeuticita[]'], true));

        $error = "";
        InsegnamentiData::updateInsegnamento((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/edit', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Insegnamento modificato correttamente</h1>'
            . view('templates/footer');
    }


    public function delete()
    {
        if (!$this->request->is('post')) {
            $insegnamento = InsegnamentiData::getInsegnamento($this->request->getGet('id'));

            return view('templates/header', ['title' => 'Segreteria'])
                . view('templates/confirmation', [
                    'submitValue' => "$insegnamento->id_insegnamento",
                    'text' => "Cacellazione insegnamento $insegnamento->id_insegnamento $insegnamento->nome $insegnamento->docente_nome $insegnamento->docente_cognome",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => '/segreteria/insegnamenti',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $postString = var_export($post, true);
        error_log("post: $postString");
        $error = "";
        InsegnamentiData::deleteInsegnamento($post['submitValue'], $error);
        if (!empty($error)) {
            $data['error'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h3>Insegnamento rimosso correttamente</h3>'
            . view('templates/footer');
    }

    public function addPropedeutico()
    {
        helper('form');
        $error = "";
        $id_insegnamento = $this->request->getGet('id_insegnamento');
        $propedeutici = InsegnamentiData::getPropedeuticiPossibiliByIdInsegnamento($id_insegnamento, $error);
        $data['id_insegnamento'] = $id_insegnamento;
        $data['propedeutici'] = $propedeutici;
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/addpropedeutico', $data)
                . view('templates/footer');
        }
        $post = $this->request->getPost(['id_richiesto', 'id_insegnamento']);
        if (
            !$this->validateData($post, [
                'id_insegnamento' => 'required',
                'id_richiesto' => 'required',
            ])
        ) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/addpropedeutico', $data)
                . view('templates/footer');
        }

        $error = "";
        InsegnamentiData::addPropedeutico($post['id_insegnamento'], $post['id_richiesto'], $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/insegnamenti/addpropedeutico', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Propedeuticità aggiunta correttamente</h1>'
            . view('templates/redirect', ['url' => "/segreteria/insegnamenti/edit?id=$id_insegnamento", 'delay' => 3])
            . view('templates/footer');
    }

    public function deletePropedeutico()
    {
        $id_insegnamento = $this->request->getGet('id_insegnamento');
        $id_richiesto = $this->request->getGet('id_richiesto');
        $error = "";
        $insegnamento = InsegnamentiData::getInsegnamento($id_insegnamento, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        $richiesto = InsegnamentiData::getInsegnamento($id_richiesto, $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        if (!$this->request->is('post')) {

            return view('templates/header', ['title' => 'Segreteria'])
                . view('templates/confirmation', [
                    'submitValue' => "$id_richiesto",
                    'text' => "Cacellazione propedeutico $richiesto->nome da $insegnamento->nome",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => "/segreteria/insegnamenti/edit?id=$id_insegnamento",
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $error = "";
        InsegnamentiData::deletePropedeutico($id_insegnamento, $post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h3>Insegnamento rimosso correttamente</h3>'
            . view('templates/redirect', ['url' => "/segreteria/insegnamenti/edit?id=$id_insegnamento", 'delay' => 3])
            . view('templates/footer');
    }

}