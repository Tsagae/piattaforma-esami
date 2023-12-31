<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class HelperData
{

    public static function getTodayDate(): string
    {
        $conn = PostgresConnection::get();
        return $conn->executeQuery("select current_date")[0]->current_date;
    }

    public static function defaultUserPassword(): string
    {
        return "password";
    }

    public static function defaultRedirectTime(): int
    {
        return 2;
    }
}