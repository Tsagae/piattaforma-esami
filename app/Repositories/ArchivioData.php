<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class ArchivioData
{


    public static function getAllStudentiArchiviati(): array
    {
        $conn = PostgresConnection::get();
        return $conn->selectProcedure("db_esami.get_all_studenti_archiviati");
    }

    public static function getAllVerbaliByMatricola(int $matricola_archiviata, string &$error): array
    {
        $conn = PostgresConnection::get();
        try {
            $res = $conn->selectProcedure("db_esami.get_all_verbali_by_matricola", $matricola_archiviata);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella selezione dei verbali";
        }
        return $res;
    }

    public static function getCarrieraValidaArchiviata(int $matricola_archiviata, string &$error): array
    {
        $conn = PostgresConnection::get();
        try {
            $res = $conn->selectProcedure("db_esami.get_carriera_valida_archiviata", $matricola_archiviata);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella selezione della carriera valida";
        }
        return $res;
    }
}