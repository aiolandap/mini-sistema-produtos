<?php

class Usuario
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function cadastrar(string $nome, string $email, string $senha): bool
    {
        $senhaHash = hash('sha256', $senha);

        $sql = "INSERT INTO usuarios (nome, email, senha_hash)
                VALUES (:nome, :email, :senha_hash)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha_hash", $senhaHash);

        return $stmt->execute();
    }

    public function emailExiste(string $email): bool
    {
        $sql = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function login(string $email, string $senha): ?array
    {
        $senhaHash = hash('sha256', $senha);

        $sql = "SELECT id, nome, email 
                FROM usuarios 
                WHERE email = :email 
                AND senha_hash = :senha_hash
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha_hash", $senhaHash);

        $stmt->execute();

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }
}