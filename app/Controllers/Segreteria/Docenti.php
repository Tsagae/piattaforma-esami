<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\HelperData;
use App\Repositories\SegretariData;
use Exception;

class Docenti extends BaseController
{
    public function listDocenti()
    {
        $data['docenti'] = DocentiData::getDocenti();
        error_log(var_export($data['docenti'], true));
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/docenti/listdocenti", $data)
            . view('templates/footer');
    }

    public function add()
    {
        helper('form');

        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/docenti/add')
                . view('templates/footer');
        }

        $post = $this->request->getPost(['nome', 'cognome']);
        error_log("post" . var_export($post, true));
        //error_log(var_export($post, true));
        // Checks whether the submitted data passed the validation rules.
        if (
            !$this->validateData($post, [
                'nome' => 'required|max_length[255]|min_length[2]',
                //TODO add more validation
                'cognome' => 'required|max_length[255]|min_length[2]',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/docenti/add')
                . view('templates/footer');
        }

        $error = "";
        $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        DocentiData::addDocente((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/docenti/add', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Docente inserito correttamente</h1>'
            . view('templates/footer');
    }

    public function delete()
    {
        if (!$this->request->is('post')) {
            $docente = DocentiData::getDocente($this->request->getGet('id'));

            return view('templates/header', ['title' => 'Segreteria'])
                . view('templates/confirmation', [
                    'submitValue' => "$docente->id_docente",
                    'text' => "Cacellazione docente $docente->id_docente $docente->nome $docente->cognome",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => '/segreteria/docenti',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $postString = var_export($post, true);
        error_log("post: $postString");
        $error = "";
        DocentiData::deleteDocente($post['submitValue'], $error);
        if (!empty($error)) {
            $data['error'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h3>Docente rimosso correttamente</h3>'
            . view('templates/footer');
    }

    public function edit()
    {
        helper('form');
        $data['userError'] = "";

        if (!$this->request->is('post')) {
            $docente = DocentiData::getDocente($this->request->getGet('id'));
            error_log("Docente: " . var_export($docente, true));
            $data['docente'] = $docente;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/docenti/edit', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['id_docente', 'nome', 'cognome', 'email', 'password']);
        //error_log(var_export($post, true));
        // Checks whether the submitted data passed the validation rules.
        if (
            !$this->validateData($post, [
                'nome' => 'required|max_length[255]|min_length[2]',
                //TODO add more validation
                'cognome' => 'required|max_length[255]|min_length[2]',
                'email' => 'required|max_length[255]|min_length[2]|valid_email',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/docenti/edit', $data)
                . view('templates/footer');
        }
        $error = "";
        if (empty($post['password'])) {
            DocentiData::updateDocenteNoPassword((object)$post, $error);
        } else {
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            DocentiData::updateDocente((object)$post, $error);
        }
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/docenti/edit', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Dati aggiornati correttamente</h1>'
            . view('templates/footer');
    }

}