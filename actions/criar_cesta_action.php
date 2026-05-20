<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Cesta.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/criar_cesta.php");
    exit;
}

try {
    $usuarioId = (int) $_SESSION["usuario_id"];

    $database = new Database();
    $conn = $database->getConnection();

    $cesta = new Cesta($conn);
    $cestaId = $cesta->criar($usuarioId);

    $_SESSION["mensagem"] = "Cesta criada com sucesso!";
    $_SESSION["tipo_mensagem"] = "success";

    header("Location: ../pages/selecionar_produtos.php?cesta_id=" . $cestaId);
    exit;

} catch (Throwable $e) {
    $_SESSION["mensagem"] = "Erro ao criar cesta: " . $e->getMessage();
    $_SESSION["tipo_mensagem"] = "danger";

    header("Location: ../pages/criar_cesta.php");
    exit;
}