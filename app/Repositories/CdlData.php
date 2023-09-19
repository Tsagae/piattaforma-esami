<?php

namespace App\Repositories;

use App\Database\PostgresConnection;
use Exception;

class CdlData
{
    /**
     * @return array
     * {
     * bool attivo,
     * string nome,
     * string tipo,
     * string id_cdl
     * }
     */
    public static function getAllCdl(): array
    {
        $conn = PostgresConnection::get();
        try {
            return $conn->executeQuery("select * from db_esami.cdl");
        } catch (Exception $e) {
            error_log($e);
        }
        return [];
    }

    public static function addCdl(object $cdl, string &$error)
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.add_cdl", $cdl->id_cdl, $cdl->nome, $cdl->tipo);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nell'inserimento dei dati";
        }
    }

    public static function getTipiLaurea(): array
    {
        $conn = PostgresConnection::get();
        return $conn->selectProcedure("db_esami.get_tipi_laurea");
    }

    public static function getCdl(string $id_cdl): ?object
    {
        $conn = PostgresConnection::get();
        $dbRes = $conn->selectProcedure("db_esami.get_cdl", $id_cdl);
        if (count($dbRes) == 0)
            return null;
        return $dbRes[0];
    }

    public static function deleteCdl(string $id_cdl, string &$error): void
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.delete_cdl", $id_cdl);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella cancellazione dei dati";
        }
    }

    public static function updateCdl(object $cdl, string &$error): void
    {
        $conn = PostgresConnection::get();
        try {
            $conn->callProcedure("db_esami.update_cdl", $cdl->id_cdl, $cdl->nome, $cdl->tipo);
        } catch (Exception $e) {
            error_log($e);
            $error = "Errore nella modifica dei dati";
        }
    }
}

