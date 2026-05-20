<?php

class Produto
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function cadastrar(
        string $nome,
        string $descricao,
        float $preco,
        int $fornecedorId
    ): bool {
        $sql = "INSERT INTO produtos (nome, descricao, preco, fornecedor_id)
                VALUES (:nome, :descricao, :preco, :fornecedor_id)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":preco", $preco);
        $stmt->bindParam(":fornecedor_id", $fornecedorId);

        return $stmt->execute();
    }

    public function listarTodos(): array
    {
        $sql = "SELECT 
                    produtos.id,
                    produtos.nome,
                    produtos.descricao,
                    produtos.preco,
                    produtos.fornecedor_id,
                    fornecedores.nome AS fornecedor_nome
                FROM produtos
                INNER JOIN fornecedores 
                    ON produtos.fornecedor_id = fornecedores.id
                ORDER BY produtos.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}