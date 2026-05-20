<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Cesta.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/criar_cesta.php");
    exit;
}

$usuarioId = (int) $_SESSION["usuario_id"];
$cestaId = (int) ($_POST["cesta_id"] ?? 0);
$produtosSelecionados = $_POST["produtos"] ?? [];

if ($cestaId <= 0) {
    $_SESSION["mensagem"] = "Cesta inválida.";
    $_SESSION["tipo_mensagem"] = "danger";
    header("Location: ../pages/criar_cesta.php");
    exit;
}

if (empty($produtosSelecionados)) {
    $_SESSION["mensagem"] = "Selecione pelo menos um produto.";
    $_SESSION["tipo_mensagem"] = "warning";
    header("Location: ../pages/selecionar_produtos.php?cesta_id=" . $cestaId);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $cestaModel = new Cesta($conn);

    $cesta = $cestaModel->buscarPorIdEUsuario($cestaId, $usuarioId);

    if (!$cesta) {
        $_SESSION["mensagem"] = "Cesta não encontrada.";
        $_SESSION["tipo_mensagem"] = "danger";
        header("Location: ../pages/criar_cesta.php");
        exit;
    }

    foreach ($produtosSelecionados as $produtoId) {
        $cestaModel->adicionarProduto($cestaId, (int) $produtoId);
    }

    $_SESSION["mensagem"] = "Produtos adicionados à cesta com sucesso!";
    $_SESSION["tipo_mensagem"] = "success";

    header("Location: ../pages/cesta.php?cesta_id=" . $cestaId);
    exit;

} catch (Throwable $e) {
    $_SESSION["mensagem"] = "Erro ao adicionar produtos: " . $e->getMessage();
    $_SESSION["tipo_mensagem"] = "danger";

    header("Location: ../pages/selecionar_produtos.php?cesta_id=" . $cestaId);
    exit;
}