<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\HelperData;
use App\Repositories\SegretariData;
use Exception;

class Cdl extends BaseController
{
    public function listCdl()
    {
        $data['allCdl'] = CdlData::getAllCdl();
        return view('templates/header', ['title' => 'Segreteria'])
            . view("segreteria/cdl/listcdl", $data)
            . view('templates/footer');
    }

    public function add()
    {
        helper('form');
        $data['tipi'] = CdlData::getTipiLaurea();
        if (!$this->request->is('post')) {
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/cdl/add', $data)
                . view('templates/footer');
        }
        $post = $this->request->getPost(['id_cdl', 'nome', 'tipo']);
        //error_log(var_export($post, true));
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'nome' => 'required|max_length[255]|min_length[2]', //TODO add more validation
            'id_cdl' => 'required|max_length[255]|min_length[3]|regex_match[/.+-.+/]',
            'tipo' => 'required|max_length[255]|min_length[3]',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/cdl/add', $data)
                . view('templates/footer');
        }

        $error = "";
        CdlData::addCdl((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/cdl/add', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Cdl aggiunto correttamente</h1>'
            . view('templates/redirect', ['url' => '/segreteria/cdl', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function delete()
    {
        if (!$this->request->is('post')) {
            $cdl = CdlData::getCdl($this->request->getGet('id'));

            return view('templates/header', ['title' => 'Segreteria'])
                . view('templates/confirmation', [
                    'submitValue' => "$cdl->id_cdl",
                    'text' => "Cacellazione cdl $cdl->id_cdl $cdl->nome",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => '/segreteria/cdl',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $postString = var_export($post, true);
        error_log("post: $postString");
        $error = "";
        CdlData::deleteCdl($post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h3>Corso di laurea rimosso correttamente</h3>'
            . view('templates/redirect', ['url' => '/segreteria/cdl', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function edit()
    {
        helper('form');
        $data['userError'] = "";
        $data['tipi'] = CdlData::getTipiLaurea();
        if (!$this->request->is('post')) {
            $cdl = CdlData::getCdl($this->request->getGet('id'));
            $data['cdl'] = $cdl;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/cdl/edit', $data)
                . view('templates/footer');
        }

        $post = $this->request->getPost(['id_cdl', 'nome', 'tipo']);
        // Checks whether the submitted data passed the validation rules.
        if (!$this->validateData($post, [
            'id_cdl' => 'required|max_length[255]|min_length[3]|regex_match[/.+-.+/]',
            'nome' => 'required|max_length[255]|min_length[2]',
            'tipo' => 'required|max_length[255]|min_length[2]',
        ])) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/cdl/edit', $data)
                . view('templates/footer');
        }
        $error = "";
        CdlData::updateCdl((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Segreteria'])
                . view('segreteria/cdl/edit', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . '<h1>Dati aggiornati correttamente</h1>'
            . view('templates/redirect', ['url' => '/segreteria/cdl', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

}