<?php

class Cesta
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function criar(int $usuarioId): int
    {
        $sql = "INSERT INTO cestas (usuario_id) VALUES (:usuario_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario_id", $usuarioId);
        $stmt->execute();

        return (int) $this->conn->lastInsertId();
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        $sql = "SELECT 
                    cestas.id,
                    cestas.criado_em,
                    COUNT(cesta_produtos.produto_id) AS total_produtos,
                    COALESCE(SUM(produtos.preco), 0) AS valor_total
                FROM cestas
                LEFT JOIN cesta_produtos 
                    ON cestas.id = cesta_produtos.cesta_id
                LEFT JOIN produtos 
                    ON cesta_produtos.produto_id = produtos.id
                WHERE cestas.usuario_id = :usuario_id
                GROUP BY cestas.id, cestas.criado_em
                ORDER BY cestas.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario_id", $usuarioId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function buscarPorIdEUsuario(int $cestaId, int $usuarioId): ?array
    {
        $sql = "SELECT * FROM cestas
                WHERE id = :id AND usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $cestaId);
        $stmt->bindParam(":usuario_id", $usuarioId);
        $stmt->execute();

        $cesta = $stmt->fetch();

        return $cesta ?: null;
    }

    public function adicionarProduto(int $cestaId, int $produtoId): bool
    {
        $sql = "INSERT INTO cesta_produtos (cesta_id, produto_id)
                VALUES (:cesta_id, :produto_id)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":cesta_id", $cestaId);
        $stmt->bindParam(":produto_id", $produtoId);

        return $stmt->execute();
    }

    public function listarProdutosDaCesta(int $cestaId, int $usuarioId): array
    {
        $sql = "SELECT 
                    produtos.id,
                    produtos.nome,
                    produtos.descricao,
                    produtos.preco,
                    fornecedores.nome AS fornecedor_nome
                FROM cesta_produtos
                INNER JOIN cestas 
                    ON cesta_produtos.cesta_id = cestas.id
                INNER JOIN produtos 
                    ON cesta_produtos.produto_id = produtos.id
                INNER JOIN fornecedores 
                    ON produtos.fornecedor_id = fornecedores.id
                WHERE cesta_produtos.cesta_id = :cesta_id
                AND cestas.usuario_id = :usuario_id
                ORDER BY produtos.nome ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":cesta_id", $cestaId);
        $stmt->bindParam(":usuario_id", $usuarioId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}