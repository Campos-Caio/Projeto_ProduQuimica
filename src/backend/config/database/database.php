<?php
class Database {
    private $host = '127.0.0.1:3306'; 
    private $user = 'root';           
    private $password = 'root';       
    private $dbname = 'produquimica'; 
    private $connection;              

    // Método para conectar ao banco de dados
    public function connect() {
        try {
            // Criar conexão usando MySQLi
            $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbname);

            // Verificar conexão
            if ($this->connection->connect_error) {
                throw new Exception('Erro na conexão: ' . $this->connection->connect_error);
            }

            // Exibir mensagem de sucesso no terminal
            echo "Conexão ao banco de dados estabelecida com sucesso!" . PHP_EOL;

            return $this->connection;
        } catch (Exception $e) {
            // Registrar erro no log do sistema
            error_log("Erro ao conectar ao banco de dados: " . $e->getMessage());

            // Exibir mensagem amigável no terminal
            echo "Erro crítico: não foi possível conectar ao banco de dados. Por favor, verifique as configurações." . PHP_EOL;
            echo "Erro técnico: " . $e->getMessage() . PHP_EOL;

            // Lançar exceção para que o script principal lide com o erro
            throw $e;
        }
    }

    // Método para fechar a conexão
    public function close() {
        if ($this->connection) {
            $this->connection->close();
            echo "Conexão ao banco de dados encerrada." . PHP_EOL;
        }
    }
}