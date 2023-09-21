<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\HelperData;
use App\Repositories\StudentiData;
use Exception;

class Studenti extends BaseController
{
    public function listStudenti()
    {
        $data['studenti'] = StudentiData::getStudenti();
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/studenti/liststudenti", $data)
            . view('templates/footer');
    }

    public function add()
    {
        helper('form');
        $data['userError'] = "";
        $data['allCdl'] = CdlData::getAllCdl();

        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/studenti/add', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['nome', 'cognome', 'cdl']);
        //error_log(var_export($post, true));
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'nome' => 'required|max_length[255]|min_length[2]', //TODO add more validation
            'cognome' => 'required|max_length[255]|min_length[2]',
            'cdl' => 'required',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/studenti/add', $data)
                . view('templates/footer');
        }

        $error = "";
        $post['password'] = password_hash(HelperData::defaultUserPassword(), PASSWORD_DEFAULT);
        StudentiData::addStudente((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/studenti/add', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Studente inserito correttamente</h1>'
            . view('templates/redirect', ['url' => "/segreteria/studenti", 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function delete()
    {
        if (!$this->request->is('post')) {
            $studente = StudentiData::getStudente($this->request->getGet('id'));

            return view('templates/header', ['title' => 'Segreteria'])
                . view('templates/confirmation', [
                    'submitValue' => "$studente->matricola",
                    'text' => "Cacellazione studente $studente->matricola $studente->nome $studente->cognome",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => '/segreteria/studenti',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }
        error_log("post");

        $post = $this->request->getPost(['submitValue']);

        $postString = var_export($post, true);
        error_log("post: $postString");
        $error = "";
        StudentiData::deleteStudente($post['submitValue'], $error);
        if (!empty($error)) {
            $data['error'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h3>Studente rimosso correttamente</h3>'
            . view('templates/redirect', ['url' => "/segreteria/studenti", 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function edit()
    {
        helper('form');
        $data['userError'] = "";

        if (!$this->request->is('post')) {
            $studente = StudentiData::getStudente($this->request->getGet('id'));
            $data['studente'] = $studente;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/studenti/edit', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['matricola', 'nome', 'cognome', 'email', 'password']);
        //error_log(var_export($post, true));
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'nome' => 'required|max_length[255]|min_length[2]', //TODO add more validation
            'cognome' => 'required|max_length[255]|min_length[2]',
            'email' => 'required|max_length[255]|min_length[2]|valid_email',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/studenti/edit', $data)
                . view('templates/footer');
        }
        $error = "";
        if (empty($post['password'])) {
            StudentiData::updateStudenteNoPassword((object)$post, $error);
        } else {
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            StudentiData::updateStudente((object)$post, $error);
        }
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/studenti/edit', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Dati aggiornati correttamente</h1>'
            . view('templates/redirect', ['url' => "/segreteria/studenti", 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

}