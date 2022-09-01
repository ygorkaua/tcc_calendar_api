<?php
/**
 * @author Ygor Barcelos
 */
declare(strict_types=1);

require_once mysqli::class;

/**
 * Class to connect on database
 */
class Database
{
    protected $connection;

    /**
     * Class constructor
     *
     * @throws Exception
     */
    public function __construct()
    {
        try {
//            $this->connection = new mysqli(
//                DB_HOST,
//                DB_USERNAME,
//                DB_PASSWORD,
//                DB_DATABASE_NAME
//            );

            $this->connection = new mysqli(
                'localhost',
                'root',
                'Passw0rd',
                'psico_calendar'
            );

            if (mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Select element from database
     *
     * @param string $query
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function select(string $query, array $params): array
    {
        try {
            $stmt = $this->executeStatement($query , $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $result;
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }

    /**
     * Select element from database
     *
     * @param string $query
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function insert(string $query, array $params): array
    {
        try {
            $stmt = $this->executeStatement($query , $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $result;
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }

    /**
     * Execute provided statement
     *
     * @param string $query
     * @param array $params
     * @return mysqli_stmt
     * @throws Exception
     */
    private function executeStatement(string $query, array $params): mysqli_stmt
    {
        try {
            $stmt = $this->connection->prepare($query);

            if ($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if ($params) {
                $stmt->bind_param($params[0], $params[1]);
            }

            $stmt->execute();

            return $stmt;
        } catch(Exception $e) {
            throw New Exception($e->getMessage());
        }
    }
}