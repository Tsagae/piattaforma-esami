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

    public static function getEsamiFuturiByIdDocente(int $id_docente, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_futuri_by_id_docente", $id_docente);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsamiPassatiByIdDocente(int $id_docente, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_passati_by_id_docente", $id_docente);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsame($id_esame, string &$error): ?object
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

    public static function updateEsame(object $esame, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.update_esame",
                $esame->id_esame,
                $esame->data,
            );
        } catch (Exception $e) {
            $error = "Impossibile aggiornare esame";
            error_log($e->getMessage());
        }
    }

    public static function getEsamiNotIscritto(int $matricola, &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_not_iscritto", $matricola);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsamiByStudente(int $id_studente, &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_by_studente", $id_studente);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsamiByDocente(int $id_docente, &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_by_docente", $id_docente);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsamiBySegretario(int $id_segretario, &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_by_segretario", $id_segretario);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getEsamiByCdl(string $id_cdl, &$error)
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_esami_by_cdl", $id_cdl);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }
    public static function iscriviStudenteAEsame(int $matricola, int $id_esame, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.iscrivi_studente_a_esame",
                $matricola,
                $id_esame,
            );
        } catch (Exception $e) {
            $error = "Impossibile iscriversi all'esame";
            error_log($e->getMessage());
        }
    }

    public static function getNextEsamiIscritto(int $matricola, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_next_esami_iscritto", $matricola);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function deleteIscrizioneEsame(int $matricola, int $id_esame, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.delete_iscrizione_esame",
                $matricola,
                $id_esame,
            );
        } catch (Exception $e) {
            $error = "Impossibile eliminare iscrizione esame";
            error_log($e->getMessage());
        }
    }

    public static function getAllEsamiIscritto(int $matricola, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_all_esami_iscritto", $matricola);
        } catch (Exception $e) {
            $error = "Impossibile recuperare esami";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getIscrizioniByIdEsame(int $id_esame, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            return $conn->selectProcedure("db_esami.get_iscrizioni_by_id_esame", $id_esame);
        } catch (Exception $e) {
            $error = "Impossibile recuperare iscritti";
            error_log($e->getMessage());
            return [];
        }
    }

    public static function getIscrizioneEsame(int $matricola, int $id_esame, string &$error): ?object
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $res = $conn->selectProcedure("db_esami.get_iscrizione_esame", $matricola, $id_esame);
            if (count($res) === 0) {
                return null;
            }
            return $res[0];
        } catch (Exception $e) {
            $error = "Impossibile recuperare iscrizione";
            error_log($e->getMessage());
            return null;
        }
    }

    public static function updateValutazione(object $valutazione, string &$error): void
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $conn->callProcedure(
                "db_esami.update_valutazione",
                $valutazione->id_esame,
                $valutazione->matricola,
                $valutazione->voto,
            );
        } catch (Exception $e) {
            $error = "Impossibile aggiornare valutazione";
            error_log($e->getMessage());
        }
    }

    public static function getCarrieraValida(int $matricola, string &$error): array
    {
        $conn = PostgresConnection::get();
        $error = "";
        try {
            $res = $conn->selectProcedure("db_esami.get_carriera_valida", $matricola);
            return $res;
        } catch (Exception $e) {
            $error = "Impossibile recuperare carriera";
            error_log($e->getMessage());
            return [];
        }
    }
}