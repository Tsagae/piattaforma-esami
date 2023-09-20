<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\HelperData;
use App\Repositories\SegretariData;
use Exception;
use Faker\Extension\Helper;

class Segretari extends BaseController
{
    public function listSegretari()
    {
        $data['segretari'] = SegretariData::getSegretari();
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/segretari/listsegretari", $data)
            . view('templates/footer');
    }

    public function add()
    {
        helper('form');

        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/segretari/add')
                . view('templates/footer');
        }

        $post = $this->request->getPost(['nome', 'cognome']);
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'nome' => 'required|max_length[255]|min_length[2]', //TODO add more validation
            'cognome' => 'required|max_length[255]|min_length[2]',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/segretari/add')
                . view('templates/footer');
        }

        $error = "";
        $post['password'] = password_hash(HelperData::defaultUserPassword(), PASSWORD_DEFAULT);
        SegretariData::addSegretario((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/segretari/add', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Segretario inserito correttamente</h1>'
            . view('templates/footer');
    }

    public function delete()
    {
        if (!$this->request->is('post')) {
            $segretario = SegretariData::getSegretario($this->request->getGet('id'));

            return view('templates/header', ['title' => 'Segreteria'])
                . view('templates/confirmation', [
                    'submitValue' => "$segretario->id_segreteria",
                    'text' => "Cacellazione segretario $segretario->id_segreteria $segretario->nome $segretario->cognome",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => '/segreteria/segretari',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $postString = var_export($post, true);
        error_log("post: $postString");
        $error = "";
        SegretariData::deleteSegretario($post['submitValue'], $error);
        if (!empty($error)) {
            $data['error'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h3>Segretario rimosso correttamente</h3>'
            . view('templates/footer');
    }

    public function edit()
    {
        helper('form');
        $data['userError'] = "";

        if (!$this->request->is('post')) {
            $segretario = SegretariData::getSegretario($this->request->getGet('id'));
            $data['segretario'] = $segretario;
            error_log("segretario: " . var_export($segretario, true));
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/segretari/edit', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['id_segreteria', 'nome', 'cognome', 'email', 'password']);
        //error_log(var_export($post, true));
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'nome' => 'required|max_length[255]|min_length[2]', //TODO add more validation
            'cognome' => 'required|max_length[255]|min_length[2]',
            'email' => 'required|max_length[255]|min_length[2]|valid_email',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/segretari/edit', $data)
                . view('templates/footer');
        }
        $error = "";
        if (empty($post['password'])) {
            SegretariData::updateSegretarioNoPassword((object)$post, $error);
        } else {
            $post['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
            SegretariData::updateSegretario((object)$post, $error);
        }
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/segretari/edit', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Dati aggiornati correttamente</h1>'
            . view('templates/footer');
    }

}