<?php

namespace App\Controllers\Docenti;

use App\Controllers\BaseController;
use App\Repositories\EsamiData;
use App\Repositories\HelperData;
use App\Repositories\InsegnamentiData;

class GestioneEsami extends BaseController
{
    private function autenticaDocente(int $id_docente): ?string
    {
        if (session()->get('docente')->id_docente != $id_docente) {
            return view('templates/header', ['title' => 'Docenti'])
                . '<h1>Accesso negato</h1>'
                . view('templates/redirect', ['url' => '/', 'delay' => HelperData::defaultRedirectTime()])
                . view('templates/footer');
        }
        return null;
    }

    public function index(): string
    {
        return view('templates/header', ['title' => 'Docenti'])
            . view('docenti/index')
            . view('templates/footer');
    }

    public function addGet(): string
    {
        helper('form');

        $get = $this->request->getGet(['id']);

        $error = "";
        $insegnamento = InsegnamentiData::getInsegnamento($get['id'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
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
        $resAuth = $this->autenticaDocente($insegnamento->id_docente);
        if ($resAuth != null)
            return $resAuth;

        $data['insegnamento'] = $insegnamento;
        $data['docente'] = $docente;
        error_log("insegnamento: " . var_export($insegnamento, true));
        return view('templates/header', ['title' => 'Docenti'])
            . view('docenti/esami/add', $data)
            . view('templates/footer');
    }

    public function addPost(): string
    {
        helper('form');

        $data = [];
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
        $insegnamento = InsegnamentiData::getInsegnamento($post['id_insegnamento'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }

        $resAuth = $this->autenticaDocente($insegnamento->id_docente);
        if ($resAuth != null)
            return $resAuth;

        $error = "";
        EsamiData::addEsame((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Docenti'])
                . "<h1>$error</h1>"
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Docenti'])
            . '<h1>Esame aggiunto correttamente</h1>'
            . view('templates/redirect', ['url' => '/docenti/insegnamenti', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function deleteGet(): string
    {
        helper('form');

        $error = "";
        $esame = EsamiData::getEsame($this->request->getGet('id'), $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        if (session()->get('docente')->id_docente != $esame->id_docente) {
            return view('templates/header', ['title' => 'Docenti'])
                . '<h1>Accesso negato</h1>'
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

    public function deletePost(): string
    {
        helper('form');

        $post = $this->request->getPost(['submitValue']);
        $error = "";
        $esame = EsamiData::getEsame($post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        if (session()->get('docente')->id_docente != $esame->id_docente) {
            return view('templates/header', ['title' => 'Docenti'])
                . '<h1>Accesso negato</h1>'
                . view('templates/footer');
        }
        EsamiData::deleteEsame($post['submitValue'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Docenti'])
            . '<h3>Insegnamento rimosso correttamente</h3>'
            . view('templates/redirect', ['url' => '/docenti/insegnamenti', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function editGet(): string
    {
        helper('form');

        $error = "";
        $esame = EsamiData::getEsame($this->request->getGet('id'), $error);

        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        $insegnamento = InsegnamentiData::getInsegnamento($esame->id_insegnamento, $error);
        $data['$insegnamento'] = $insegnamento;
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        $resAuth = $this->autenticaDocente($insegnamento->id_docente);
        if ($resAuth != null)
            return $resAuth;

        return view('templates/header', ['title' => 'Docenti'])
            . view('docenti/esami/edit', ['esame' => $esame])
            . view('templates/footer');
    }

    public function editPost(): string
    {
        helper('form');

        $data = [];
        $error = "";
        $esame = EsamiData::getEsame($this->request->getGet('id'), $error);


        $post = $this->request->getPost(['id_esame', 'data']);
        if (
            !$this->validateData($post, [
                'id_esame' => 'required',
                'data' => 'required',
            ])
        ) {
            // The validation fails, so returns the form.
            $esame->data = $post['data'];
            return view('templates/header', ['title' => 'Docenti'])
                . view('docenti/esami/edit', ['esame' => $esame])
                . view('templates/footer');
        }

        $error = "";
        $esame = EsamiData::getEsame($post['id_esame'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        $resAuth = $this->autenticaDocente($esame->id_docente);
        if ($resAuth != null)
            return $resAuth;
        EsamiData::updateEsame((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Docenti'])
                . "<h1>$error</h1>"
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Docenti'])
            . '<h3>Esame modificato correttamente</h3>'
            . view('templates/redirect', ['url' => '/docenti/insegnamenti', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }

    public function listValutazioni(): string
    {
        helper('form');

        $get = $this->request->getGet(['id']);

        $error = "";
        $esame = EsamiData::getEsame($get['id'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }
        $resAuth = $this->autenticaDocente($esame->id_docente);
        if ($resAuth != null)
            return $resAuth;

        $studenti = EsamiData::getIscrizioniByIdEsame($get['id'], $error);
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Docenti'])
                . esc($error)
                . view('templates/footer');
        }

        $itemsStudenti = [];
        foreach ($studenti as $studente) {
            $item = new \stdClass();
            $item->head = "Matricola: " . $studente->matricola;
            $item->body = [$studente->nome, $studente->cognome, "Voto: $studente->voto"];
            $item->buttons = [
                (object)[
                    "link" => "/docenti/esami/valutazioni/valuta?matricola=$studente->matricola&idesame=$esame->id_esame",
                    "style" => "btn btn-primary m-1",
                    "text" => "Valuta"
                ],
            ];
            $itemsStudenti[] = $item;
        }

        $data['items'] = $itemsStudenti;
        $data['noRecordsText'] = "Non ci sono studenti iscritti a questo esame";
        $title = esc("Valutazioni " . $esame->nome_insegnamento . " " . $esame->id_cdl . " " . $esame->data);
        $numIscritti = count($studenti);
        return view('templates/header', ['title' => 'Docenti'])
            . "<h1>$title</h1>"
            . "Iscritti: $numIscritti"
            . view("templates/list", $data)
            . view('templates/footer');
    }

    public function valutaGet(): string
    {
        helper('form');

        $get = $this->request->getGet(['matricola', 'idesame']);

        $error = "";
        $valutazione = EsamiData::getIscrizioneEsame($get['matricola'], $get['idesame'], $error);
        if (!empty($error)) {
            $data['error'] = $error;
            return view('templates/header', ['title' => 'Docenti'])
                . view('templates/valutazione', $data)
                . view('templates/footer');
        }

        $resAuth = $this->autenticaDocente($valutazione->id_docente);
        if ($resAuth != null)
            return $resAuth;


        $data['valutazione'] = $valutazione;
        return view('templates/header', ['title' => 'Docenti'])
            . view('docenti/valutazione', $data)
            . view('templates/footer');
    }

    public function valutaPost(): string
    {
        helper('form');

        $data = [];
        $error = "";
        $valutazione = new \stdClass();
        $post = $this->request->getPost(['id_esame', 'id_docente', 'matricola', 'voto']);
        if (
            !$this->validateData($post, [
                'id_esame' => 'required',
                'id_docente' => 'required',
                'matricola' => 'required',
                'voto' => 'required',
            ])
        ) {
            $valutazione = EsamiData::getIscrizioneEsame($post['matricola'], $post['idesame'], $error);
            if (!empty($error)) {
                $data['error'] = $error;
                return view('templates/header', ['title' => 'Docenti'])
                    . view('templates/valutazione', $data)
                    . view('templates/footer');
            }
            return view('templates/header', ['title' => 'Docenti'])
                . view('docenti/esami/edit', ['valutazione' => $valutazione])
                . view('templates/footer');
        }

        $resAuth = $this->autenticaDocente($post['id_docente']);
        if ($resAuth != null)
            return $resAuth;

        EsamiData::updateValutazione((object)$post, $error);
        if (!empty($error)) {
            error_log("error");
            $data['queryError'] = $error;
            return view('templates/header', ['title' => 'Docenti'])
                . "$error"
                . view('templates/footer');
        }
        return view('templates/header', ['title' => 'Docenti'])
            . '<h3>Valutazione aggiornata correttamente</h3>'
            . view('templates/redirect', ['url' => '/', 'delay' => HelperData::defaultRedirectTime()])
            . view('templates/footer');
    }
}