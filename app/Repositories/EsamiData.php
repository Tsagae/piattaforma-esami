<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class EsamiData
{
    public static function addEsame(object $esame, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.add_esame",
                $esame->data,
                $esame->id_insegnamento,
                $esame->id_docente,
            );
        } catch (Exception $e) {
            $error = "Impossibile aggiungere esame";
            error_log($e->getMessage());
        }
    }

    public static function getEsamiByIdDocente(int $id_docente, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_by_id_docente", $id_docente);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsame(int $id_esame, string &$error): ?object
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $res = $conn->selectProcedure("db_esami.get_esame", $id_esame);
            if (count($res) === 0) {
                return null;
            }
            return $res[0];
        } catch (Exception $e) {
            $error = "Impossibile recuperare esame";
            error_log($e->getMessage());
            return null;
        }
    }

    public static function deleteEsame(int $id_esame, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure("db_esami.delete_esame", $id_esame);
        } catch (Exception $e) {
            $error = "Impossibile eliminare esame";
            error_log($e->getMessage());
        }
    }
}