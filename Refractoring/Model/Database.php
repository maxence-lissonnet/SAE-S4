<?php
class DB
{
    private $host = 'localhost';
    private $db = 'sae';
    private $db_user = 'root';
    private $db_pass = '';

    public function connect()
    {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            return new PDO($dsn, $this->db_user, $this->db_pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
