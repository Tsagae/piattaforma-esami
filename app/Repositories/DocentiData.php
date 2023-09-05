<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class DocentiData
{


    public static function getDocenti()
    {
        $conn = PostgresConnection::get();
        return $conn->executeQuery("select * from db_esami.docenti_info");
    }

    public static function addDocente(object $docente, string &$error)
    {
        //error_log(var_export($studente, true));
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.add_docente", $docente->nome, $docente->cognome);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'inserimento dei dati";
        }
    }


    public static function getDocente(int $id_docente)
    {
        $conn = PostgresConnection::get();
        $res = $conn->selectProcedure("db_esami.get_docente", $id_docente);
        if (count($res) === 0) {
            return null;
        }
        return $res[0];
    }

    public static function deleteDocente(int $id_docente, string &$error)
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.delete_docente", $id_docente);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella rimozione del docente";
        }
    }

    public static function updateDocente(object $docente, string &$error)
    {
        //error_log("new password: $studente->password");

        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure(
                "db_esami.update_docente",
                $docente->id_docente,
                $docente->nome,
                $docente->cognome,
                $docente->email,
                $docente->password
            );
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'aggiornamento del docente";
        }
    }

    public static function updateDocenteNoPassword(object $docente, string &$error)
    {
        $docenteFromDb = self::getDocente($docente->id_docente);
        $docente->password = $docenteFromDb->password;
        self::updateDocente($docente, $error);
    }

    public static function getDocenteByIdUtente(int $id_utente, &$error): ?object
    {
        $conn = PostgresConnection::get();
        try {
            $res = $conn->selectProcedure("db_esami.get_docente_by_id_utente", $id_utente);
            if (count($res) === 0) {
                $error = "Errore nella selezione del docente";
                return null;
            }
            return $res[0];
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella selezione del docente";
        }
        return null;
    }
}