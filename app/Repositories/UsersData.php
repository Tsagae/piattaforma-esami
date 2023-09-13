<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class UsersData
{
    /**
     * @param string $email
     * @param string $error
     * @return object|null {id_utente, email, password}
     */
    public static function getUtenteByEmail(string $email, string &$error): ?object
    {
        $conn = PostgresConnection::get();
        try {
            $res = $conn->selectProcedure("db_esami.getutentebyemail", $email);
        } catch (Exception $e) {
            log_message('error', $e);
            $error = "Errore nella connessione al db";
            return null;
        }
        if ($res === false) {
            $error = "Errore nella query";
            return null;
        } else {
            if (count($res) == 1) {
                return $res[0];
            }
        }
        return null;
    }

    public static function updateUserPassword(int $id_utente, string $password, string &$error): void
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure(
                "db_esami.update_user_password",
                $id_utente,
                $password
            );
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'aggiornamento della password";
        }
    }
}