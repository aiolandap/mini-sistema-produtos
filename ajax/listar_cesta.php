<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Cesta.php";

header("Content-Type: application/json; charset=UTF-8");

$cestaId = (int) ($_GET["cesta_id"] ?? 0);
$usuarioId = (int) $_SESSION["usuario_id"];

if ($cestaId <= 0) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Cesta inválida."
    ]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $cestaModel = new Cesta($conn);

    $cesta = $cestaModel->buscarPorIdEUsuario($cestaId, $usuarioId);

    if (!$cesta) {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Cesta não encontrada."
        ]);
        exit;
    }

    $produtos = $cestaModel->listarProdutosDaCesta($cestaId, $usuarioId);

    $totalProdutos = count($produtos);
    $valorTotal = 0;

    foreach ($produtos as $produto) {
        $valorTotal += (float) $produto["preco"];
    }

    echo json_encode([
        "status" => "sucesso",
        "cesta_id" => $cestaId,
        "total_produtos" => $totalProdutos,
        "valor_total" => $valorTotal,
        "produtos" => $produtos
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao carregar cesta: " . $e->getMessage()
    ]);
    exit;
}