<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class StudentiData
{


    public static function getStudenti(): array
    {
        $conn = PostgresConnection::get();
        return $conn->executeQuery("select * from db_esami.studenti_info");
    }

    public static function getStudente(int $matricola): ?object
    {
        $conn = PostgresConnection::get();
        $res = $conn->selectProcedure("db_esami.get_studente", $matricola);
        if (count($res) === 0) {
            return null;
        }
        return $res[0];
    }


    /**
     * @param object $studente
     * @param string $error
     * @return void
     */
    public static function addStudente(object $studente, string &$error): void
    {
        //error_log(var_export($studente, true));
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.add_studente", $studente->nome, $studente->cognome, $studente->cdl);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'inserimento dei dati";
        }
    }

    /**
     * @param int $matricola
     * @param string $error
     * @return void
     */
    public static function deleteStudente(int $matricola, string &$error): void
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.delete_studente", $matricola);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella rimozione dello studente";
        }
    }

    public static function updateStudente(object $studente, string &$error)
    {
        //error_log("new password: $studente->password");

        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure(
                "db_esami.update_studente",
                $studente->matricola,
                $studente->nome,
                $studente->cognome,
                $studente->email,
                $studente->password
            );
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'aggiornamento dello studente";
        }
    }

    public static function updateStudenteNoPassword(object $studente, string &$error)
    {
        $studenteFromDb = self::getStudente($studente->matricola);
        $studente->password = $studenteFromDb->password;
        self::updateStudente($studente, $error);
    }

}
