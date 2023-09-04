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

    public static function getInsegnamentiByCdl(string $id_cdl): array
    {
        $conn = PostgresConnection::get();
        return $conn->selectProcedure("db_esami.get_insegnamenti_by_cdl", $id_cdl);
    }
}