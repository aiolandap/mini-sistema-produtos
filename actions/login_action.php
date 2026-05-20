<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

if (empty($email) || empty($senha)) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    $_SESSION["tipo_mensagem"] = "danger";
    header("Location: ../login.php");
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $usuarioModel = new Usuario($conn);
    $usuario = $usuarioModel->login($email, $senha);

    if (!$usuario) {
        $_SESSION["mensagem"] = "E-mail ou senha inválidos.";
        $_SESSION["tipo_mensagem"] = "danger";
        header("Location: ../login.php");
        exit;
    }

    $_SESSION["usuario_id"] = $usuario["id"];
    $_SESSION["usuario_nome"] = $usuario["nome"];
    $_SESSION["usuario_email"] = $usuario["email"];

    header("Location: ../pages/dashboard.php");
    exit;

} catch (PDOException $e) {
    $_SESSION["mensagem"] = "Erro ao fazer login: " . $e->getMessage();
    $_SESSION["tipo_mensagem"] = "danger";

    header("Location: ../login.php");
    exit;
}