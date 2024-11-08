<?php

class Database {
    private $host = '127.0.0.1';
    private $db_name = 'produquimica';
    private $username = 'root';
    private $password = 'root';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            if ($this->conn->connect_error) {
                throw new Exception("Erro de conexão: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }

        return $this->conn;
    }
}
