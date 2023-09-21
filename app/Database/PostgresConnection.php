<?php

namespace App\Database;

use CodeIgniter\Database\Database;
use Exception;
use Pgsql\Connection;
use \PgSql\Result;

/**
 * Represent the Connection
 */
class PostgresConnection
{

    /**
     * Connection
     */
    private static $conn;


    /**
     * @return Connection
     * @throws Exception
     */
    private function connect(): Connection
    {
        //echo "CONSTRUCTOR FOR PostgresConnection CALLED\n";
        // read parameters in the ini configuration file
        $params = parse_ini_file(APPPATH . '../.env');
        if ($params === false) {
            throw new Exception("Error reading database configuration file");
        }
        // connect to the postgresql database
        $conStr = sprintf(
            "host=%s dbname=%s user=%s password=%s",
            $params['database.default.hostname'],
            $params['database.default.database'],
            $params['database.default.username'],
            $params['database.default.password']
        );

        $conn = pg_connect($conStr) or die("connection failed");

        return $conn;
    }


    /**
     * @param string $sql
     * @return array|bool
     * @throws Exception
     */
    public function executeQuery(string $sql): array|bool
    {
        $conn = $this->connect();
        $dbRes = pg_query($conn, $sql);
        if ($dbRes === false)
            return false;
        $resData = array();
        while ($dbData = pg_fetch_object($dbRes)) {
            $resData[] = $dbData;
        }
        return $resData;
    }


    /**
     * @param string $query
     * @param array $argArr
     * @return array|bool
     * @throws Exception
     */
    private function query_params(string $query, array $argArr): bool|array
    {
        //Costruzione della stringa di placeholder per la query parametrizzata
        $procArgs = '(';
        for ($i = 0; $i < count($argArr); $i++) {
            $sArg = $i + 1;
            $procArgs .= "\$$sArg, ";
        }
        $procArgs = rtrim($procArgs);
        $procArgs = rtrim($procArgs, ',');
        $procArgs .= ')';
        //Connessione al database ed esecuzione della query
        $conn = $this->connect();
        $dbRes = pg_query_params($conn, "$query$procArgs;", $argArr);
        if ($dbRes === false)
            return false;
        $resData = array();
        while ($dbData = pg_fetch_object($dbRes)) {
            $resData[] = $dbData;
        }
        return $resData;
    }


    /**
     * @param string $procedureName
     * @return array|bool
     * @throws Exception
     */
    public function callProcedure(string $procedureName): array|bool
    {
        return $this->query_params("CALL $procedureName", array_slice(func_get_args(), 1));
    }


    /**
     * @param string $procedureName
     * @return array|bool
     * @throws Exception
     */
    public function selectProcedure(string $procedureName): array|bool
    {
        return $this->query_params("SELECT * FROM $procedureName", array_slice(func_get_args(), 1));
    }

    /**
     * @return PostgresConnection
     */
    public static function get(): PostgresConnection
    {
        if (null === static::$conn) {
            //echo "instantiating new connection\n";
            static::$conn = new static();
        }

        return static::$conn;
    }


    protected function __construct()
    {

    }

    private function __clone()
    {

    }

    public function __wakeup()
    {

    }

}