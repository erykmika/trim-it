<?php

declare(strict_types=1);

namespace Database;

use Exception;
use PDO;

/**
 * Class representing a database
 */
class Database
{
    /**
     * @var PDO $pdo Connection to the database
     */
    private PDO $pdo;

    /**
     * Instantiate the database object
     * 
     * @param string $host Database host address
     * @param string $dbname Database name
     * @param string $username Database username
     * @param string $password Database password
     * @throws DatabaseException if failed to establish the database connection
     */
    public function __construct(
        string $host,
        string $dbname,
        string $username,
        string $password
    ) {
        try {
            $this->pdo = new PDO(
                "mysql:host={$host};dbname={$dbname}",
                $username,
                $password,
                [PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Perform query on the database
     * 
     * @param string $query Query to be executed
     * @param bool $fetch_result Whether fetch results of the 'query'
     * @throws DatabaseException if performing the query fails
     * @return array|null Resulting array or array of arrays provided that 'fetch_result' is 'true', 'null' otherwise 
     */
    public function query(string $query, bool $fetch_result = false): array|null
    {
        try {
            $stmt = $this->pdo->query($query);
            if ($fetch_result) {
                return $stmt->fetchAll(PDO::FETCH_CLASS);
            }
            return null;
        } catch (Exception $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
