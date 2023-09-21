<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class InsegnamentiData
{

    public static function getInsegnamenti(): array
    {
        $conn = PostgresConnection::get();
        return $conn->executeQuery("SELECT * FROM db_esami.insegnamenti_info");
    }

    public static function getInsegnamento(int $id_insegnamento, &$error): ?object
    {
        $conn = PostgresConnection::get();
        error_log("id insegnamento: $id_insegnamento \n");
        try {
            $insegnamenti = $conn->selectProcedure("db_esami.get_insegnamento", $id_insegnamento);
        } catch (Exception $e) {
            $error = "Impossibile recuperare insegnamento";
            error_log($e->getMessage());
        }
        return count($insegnamenti) > 0 ? $insegnamenti[0] : null;
    }

    public static function getInsegnamentiByCdl(string $id_cdl): array
    {
        $conn = PostgresConnection::get();
        return $conn->selectProcedure("db_esami.get_insegnamenti_by_cdl", $id_cdl);
    }

    // addInsegnamento adds a new insegnamento to the database.
    // It returns an error message if the query fails.
    public static function addInsegnamento(object $insegnamento, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.add_insegnamento",
                $insegnamento->semestre,
                $insegnamento->nome,
                $insegnamento->id_docente,
                $insegnamento->id_cdl,
                $insegnamento->anno
            );
        } catch (Exception $e) {
            $error = "Impossibile aggiungere insegnamento";
            error_log($e->getMessage());
        }
    }

    // updateInsegnamento updates an insegnamento in the database.
    // It returns an error message if the query fails.
    public static function updateInsegnamento(object $insegnamento, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.update_insegnamento",
                $insegnamento->id_insegnamento,
                $insegnamento->semestre,
                $insegnamento->nome,
                $insegnamento->id_docente,
                $insegnamento->id_cdl,
                $insegnamento->anno
            );
        } catch (Exception $e) {
            $error = "Impossibile aggiornare insegnamento";
            error_log($e->getMessage());
        }
    }

    // deleteInsegnamento deletes an insegnamento from the database.
    // It returns an error message if the query fails.
    public static function deleteInsegnamento(string $id_insegnamento, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure("db_esami.delete_insegnamento", $id_insegnamento);
        } catch (Exception $e) {
            $error = "Impossibile eliminare insegnamento";
            error_log($e->getMessage());
        }
    }

    public static function getInsegnamentiByIdDocente(int $id_docente, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_insegnamenti_by_id_docente", $id_docente);
        } catch (Exception $e) {
            $error = "Impossibile recuperare insegnamenti";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function get_insegnamenti_by_cdl(string $id_cdl, &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_insegnamenti_by_cdl", $id_cdl);
        } catch (Exception $e) {
            $error = "Impossibile recuperare insegnamenti";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getPropedeuticiByIdInsegnamento(int $id_insegnamento, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_propedeutici_by_id_insegnamento", $id_insegnamento);
        } catch (Exception $e) {
            $error = "Impossibile recuperare insegnamenti propedeutici";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getPropedeuticiPossibiliByIdInsegnamento(int $id_insegnamento, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_propedeutici_possibili_by_id_insegnamento", $id_insegnamento);
        } catch (Exception $e) {
            $error = "Impossibile recuperare insegnamenti";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function addPropedeutico(int $id_insegnamento, int $id_richiesto, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.add_propedeutico",
                $id_insegnamento,
                $id_richiesto
            );
        } catch (Exception $e) {
            $error = "Impossibile aggiungere insegnamento propedeutico";
            error_log($e->getMessage());
        }
    }


    public static function deletePropedeutico(int $id_insegnamento, int $id_richiesto, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.delete_propedeutico",
                $id_insegnamento,
                $id_richiesto
            );
        } catch (Exception $e) {
            $error = "Impossibile rimuovere insegnamento propedeutico";
            error_log($e->getMessage());
        }
    }
}