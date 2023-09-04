<?php

namespace App\Repositories;

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
        $conn = \App\Database\PostgresConnection::get();
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
            if(count($res) == 1){
                return $res[0];
            }
        }
        return null;
    }
}
