<?php

namespace App\Controllers\Docenti;


use App\Controllers\BaseController;
use App\Repositories\EsamiData;
use App\Repositories\InsegnamentiData;

class GestioneEsami extends BaseController
{
    public function index()
    {
        return view('templates/header', ['title' => 'Docenti'])
            . view('docenti/index')
            . view('templates/footer');
    }

    public function add()
    {
        helper('form');

        $data = [];
        if (!$this->request->is('post')) {
            $get = $this->request->getGet(['id']);
            $insegnamento = InsegnamentiData::getInsegnamento($get['id']);
            $docente = session()->docente;
            if ($insegnamento === null) {
                return view('templates/header', ['title' => 'Docenti'])
                    . '<h1>Insegnamento non trovato</h1>'
                    . view('templates/footer');
            }
            if ($docente === null) {
                return view('templates/header', ['title' => 'Docenti'])
                    . '<h1>Docente non valido</h1>'
                    . view('templates/footer');
            }
            $data['insegnamento'] = $insegnamento;
            $data['docente'] = $docente;
            return view('templates/header', ['title' => 'Docenti'])
                . view('docenti/esami/add', $data)
                . view('templates/footer');
        }
        $post = $this->request->getPost(['id_insegnamento', 'id_docente', 'data']);
        if (
            !$this->validateData($post, [
                'id_insegnamento' => 'required',
                //TODO add more validation
                'id_docente' => 'required',
                'data' => 'required',
            ])
        ) {
            // The validation fails, so returns the form.
            return view('templates/header', ['title' => 'Docenti'])
                . view('docenti/esami/add', $data)
                . view('templates/footer');
        }

        $error = "";
        EsamiData::addEsame((object) $post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Docenti'])
                . view('docenti/esami/add', $data)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Docenti'])
            . '<h1>Esame aggiunto correttamente</h1>'
            . view('templates/footer');
    }

    public function delete()
    {
        $error = "";
        if (!$this->request->is('post')) {
            $esame = EsamiData::getEsame($this->request->getGet('id'), $error);
            if (!empty($error)) {
                return view('templates/header', ['title' => 'Docenti'])
                    . esc($error)
                    . view('templates/footer');
            }
            return view('templates/header', ['title' => 'Docenti'])
                . view('templates/confirmation', [
                    'submitValue' => "$esame->id_esame",
                    'text' => "Cacellazione esame $esame->id_esame $esame->data $esame->nome_insegnamento $esame->id_cdl",
                    'confirmText' => 'Rimuovi',
                    'cancelRedirect' => '/docenti',
                    'cancelText' => 'Annulla'
                ])
                . view('templates/footer');
        }

        $post = $this->request->getPost(['submitValue']);

        $postString = var_export($post, true);
        error_log("post: $postString");
        $error = "";
        EsamiData::deleteEsame($post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Docenti'])
            . '<h3>Insegnamento rimosso correttamente</h3>'
            . view('templates/footer');
    }
}