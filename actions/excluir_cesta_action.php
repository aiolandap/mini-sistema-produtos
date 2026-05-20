<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/minhas_cestas.php");
    exit;
}

$cestaId = (int) ($_POST["cesta_id"] ?? 0);
$usuarioId = (int) $_SESSION["usuario_id"];

if ($cestaId <= 0) {
    header("Location: ../pages/minhas_cestas.php");
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $sql = "DELETE FROM cestas 
            WHERE id = :cesta_id 
            AND usuario_id = :usuario_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":cesta_id", $cestaId);
    $stmt->bindParam(":usuario_id", $usuarioId);
    $stmt->execute();

    header("Location: ../pages/minhas_cestas.php");
    exit;

} catch (Throwable $e) {
    echo "Erro ao excluir cesta: " . $e->getMessage();
    exit;
}