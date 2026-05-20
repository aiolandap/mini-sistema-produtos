<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Produto.php";

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Requisição inválida."
    ]);
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$descricao = trim($_POST["descricao"] ?? "");
$preco = trim($_POST["preco"] ?? "");
$fornecedorId = trim($_POST["fornecedor_id"] ?? "");

if (empty($nome) || empty($preco) || empty($fornecedorId)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Preencha nome, preço e fornecedor."
    ]);
    exit;
}

$preco = str_replace(",", ".", $preco);

if (!is_numeric($preco)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "O preço deve ser um número válido."
    ]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $produto = new Produto($conn);

    $produto->cadastrar(
        $nome,
        $descricao,
        (float) $preco,
        (int) $fornecedorId
    );

    echo json_encode([
        "status" => "sucesso",
        "mensagem" => "Produto cadastrado com sucesso!"
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao cadastrar produto: " . $e->getMessage()
    ]);
    exit;
}