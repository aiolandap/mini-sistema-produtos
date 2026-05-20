<?php

class Database
{
    private string $host = "localhost";
    private string $port = "3307";
    private string $dbName = "mini_sistema_produtos";
    private string $user = "root";
    private string $password = "";
    private ?PDO $conn = null;

    public function __construct()
    {
        $this->criarBanco();
        $this->conn = $this->conectar();
        $this->criarTabelas();
    }

    private function conectarSemBanco(): PDO
    {
        return new PDO(
            "mysql:host={$this->host};port={$this->port};charset=utf8mb4",
            $this->user,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    private function conectar(): PDO
    {
        return new PDO(
            "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4",
            $this->user,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    private function criarBanco(): void
    {
        $pdo = $this->conectarSemBanco();

        $sql = "CREATE DATABASE IF NOT EXISTS {$this->dbName}
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci";

        $pdo->exec($sql);
    }

    private function criarTabelas(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                senha_hash VARCHAR(255) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS fornecedores (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                cnpj VARCHAR(20),
                telefone VARCHAR(20),
                email VARCHAR(100),
                endereco VARCHAR(255),
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS produtos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                descricao TEXT,
                preco DECIMAL(10,2) NOT NULL,
                fornecedor_id INT NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            );

            CREATE TABLE IF NOT EXISTS cestas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            );

            CREATE TABLE IF NOT EXISTS cesta_produtos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                cesta_id INT NOT NULL,
                produto_id INT NOT NULL,
                FOREIGN KEY (cesta_id) REFERENCES cestas(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                FOREIGN KEY (produto_id) REFERENCES produtos(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
            );
        ";

        $this->conn->exec($sql);
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}