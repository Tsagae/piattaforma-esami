<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class SegretariData
{
    public static function getSegretari()
    {
        $conn = PostgresConnection::get();
        return $conn->executeQuery("select * from db_esami.segretari_info");
    }

    public static function addSegretario(object $segretario, string &$error)
    {
        //error_log(var_export($studente, true));
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.add_segretario", $segretario->nome, $segretario->cognome, $segretario->password);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'inserimento dei dati";
        }
    }


    public static function getSegretario(int $id_segreteria)
    {
        $conn = PostgresConnection::get();
        $res = $conn->selectProcedure("db_esami.get_segretario", $id_segreteria);
        if (count($res) === 0) {
            return null;
        }
        return $res[0];
    }

    public static function deleteSegretario(int $id_segreteria, string &$error)
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.delete_segretario", $id_segreteria);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella rimozione dell segretario";
        }
    }

    public static function updateSegretario(object $segretario, string &$error)
    {
        //error_log("new password: $studente->password");

        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure(
                "db_esami.update_segretario",
                $segretario->id_segreteria,
                $segretario->nome,
                $segretario->cognome,
                $segretario->email,
                $segretario->password
            );
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'aggiornamento del segretario";
        }
    }

    public static function updateSegretarioNoPassword(object $segretario, string &$error)
    {
        $segretarioFromDb = self::getSegretario($segretario->id_segreteria);
        $segretario->password = $segretarioFromDb->password;
        self::updateSegretario($segretario, $error);
    }

    public static function getSegretarioByIdUtente(int $id_utente, &$error): ?object
    {
        $conn = PostgresConnection::get();
        try {
            $res = $conn->selectProcedure("db_esami.get_segretario_by_id_utente", $id_utente);
            if (count($res) === 0) {
                $error = "Errore nella selezione del segretario";
                return null;
            }
            return $res[0];
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella selezione del segretario";
        }
        return null;
    }
}