<?php

namespace App\Controllers\Segreteria;


use App\Controllers\BaseController;
use App\Repositories\ArchivioData;
use App\Repositories\CdlData;
use App\Repositories\DocentiData;
use App\Repositories\SegretariData;
use App\Repositories\StudentiData;
use Exception;

class Archivio extends BaseController
{
    public function listStudentiArchiviati()
    {
        $studenti_archiviati = ArchivioData::getAllStudentiArchiviati();
        $items = [];
        foreach ($studenti_archiviati as $studente) {
            $item = new \stdClass();
            $item->head = "Matricola: $studente->matricola" . " " . $studente->nome . " " . $studente->cognome;
            $item->body = [$studente->id_cdl, $studente->nome_cdl, $studente->tipo_cdl, !$studente->laureato ? "Laureato" : "Non Laureato"];
            $item->buttons = [
                (object)['link' => "/segreteria/archivio/carriera?matricola=$studente->matricola", 'style' => "btn btn-primary m-1", 'text' => "Carriera"],
                (object)['link' => "/segreteria/archivio/carrieravalida?matricola=$studente->matricola", 'style' => "btn btn-primary m-1", 'text' => "Carriera Valida"]
            ];
            $items[] = $item;
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . "<h1>Studenti Archiviati</h1>"
            . view("templates/list", ['items' => $items, 'noRecordsText' => "Nessuno studente archiviato"])
            . view('templates/footer');
    }

    public function listCarrieraArchiviata()
    {
        $error = "";
        $matricola = $this->request->getGet('matricola');
        $carriera = ArchivioData::getAllVerbaliByMatricolaArchiviata($matricola, $error);
        return $this->listCarriera($carriera, $error, 'Carriera');
    }

    public function listCarrieraValida()
    {
        $error = "";
        $matricola = $this->request->getGet('matricola');
        $carriera = ArchivioData::getCarrieraValidaArchiviata($matricola, $error);
        return $this->listCarriera($carriera, $error, 'Carriera Valida');
    }

    private function listCarriera(array $carriera, string $error, string $header): string
    {
        if (!empty($error)) {
            return view('templates/header', ['title' => 'Segreteria'])
                . "<h1>Errore</h1>"
                . "<h3>$error</h3>"
                . view('templates/footer');
        }
        $items = [];
        foreach ($carriera as $esame) {
            $item = new \stdClass();
            $item->head = $esame->nome_insegnamento . " " . $esame->nome_docente . " " . $esame->cognome_docente;
            $item->body = ["Voto: $esame->voto", "Data Verbalizzazione: $esame->data_verbalizzazione"];
            $item->buttons = [];
            $items[] = $item;
        }
        return view('templates/header', ['title' => 'Segreteria'])
            . "<h1>$header</h1>"
            . view("templates/list", ['items' => $items, 'noRecordsText' => "Nessun esame in carriera"])
            . view('templates/footer');
    }

}