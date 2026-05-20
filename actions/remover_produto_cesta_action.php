<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Requisição inválida."
    ]);
    exit;
}

$usuarioId = (int) $_SESSION["usuario_id"];
$cestaId = (int) ($_POST["cesta_id"] ?? 0);
$produtoId = (int) ($_POST["produto_id"] ?? 0);

if ($cestaId <= 0 || $produtoId <= 0) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Dados inválidos."
    ]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $sql = "DELETE FROM cesta_produtos
            WHERE cesta_id = :cesta_id
            AND produto_id = :produto_id
            AND cesta_id IN (
                SELECT id FROM cestas WHERE usuario_id = :usuario_id
            )";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":cesta_id", $cestaId);
    $stmt->bindParam(":produto_id", $produtoId);
    $stmt->bindParam(":usuario_id", $usuarioId);
    $stmt->execute();

    echo json_encode([
        "status" => "sucesso",
        "mensagem" => "Produto removido da cesta com sucesso!"
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao remover produto: " . $e->getMessage()
    ]);
    exit;
}