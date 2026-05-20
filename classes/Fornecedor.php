<?php

class Fornecedor
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function cadastrar(
        string $nome,
        string $cnpj,
        string $telefone,
        string $email,
        string $endereco
    ): bool {
        $sql = "INSERT INTO fornecedores (nome, cnpj, telefone, email, endereco)
                VALUES (:nome, :cnpj, :telefone, :email, :endereco)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cnpj", $cnpj);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":endereco", $endereco);

        return $stmt->execute();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT * FROM fornecedores ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}