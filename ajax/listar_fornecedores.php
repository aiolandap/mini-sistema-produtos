<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Fornecedor.php";

header("Content-Type: application/json; charset=UTF-8");

try {
    $database = new Database();
    $conn = $database->getConnection();

    $fornecedor = new Fornecedor($conn);
    $fornecedores = $fornecedor->listarTodos();

    echo json_encode([
        "status" => "sucesso",
        "dados" => $fornecedores
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao listar fornecedores: " . $e->getMessage()
    ]);
    exit;
}