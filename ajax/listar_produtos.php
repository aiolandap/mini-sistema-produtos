<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Produto.php";

header("Content-Type: application/json; charset=UTF-8");

try {
    $database = new Database();
    $conn = $database->getConnection();

    $produto = new Produto($conn);
    $produtos = $produto->listarTodos();

    echo json_encode([
        "status" => "sucesso",
        "dados" => $produtos
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao listar produtos: " . $e->getMessage()
    ]);
    exit;
}