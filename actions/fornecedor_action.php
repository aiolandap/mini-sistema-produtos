<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Fornecedor.php";

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Requisição inválida."
    ]);
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$cnpj = trim($_POST["cnpj"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$email = trim($_POST["email"] ?? "");
$endereco = trim($_POST["endereco"] ?? "");

if (empty($nome)) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "O nome do fornecedor é obrigatório."
    ]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $fornecedor = new Fornecedor($conn);

    $fornecedor->cadastrar($nome, $cnpj, $telefone, $email, $endereco);

    echo json_encode([
        "status" => "sucesso",
        "mensagem" => "Fornecedor cadastrado com sucesso!"
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro real: " . $e->getMessage()
    ]);
    exit;
}