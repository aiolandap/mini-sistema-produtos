<?php

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Usuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro.php");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$senha = trim($_POST["senha"] ?? "");

if (empty($nome) || empty($email) || empty($senha)) {
    $_SESSION["mensagem"] = "Preencha todos os campos.";
    $_SESSION["tipo_mensagem"] = "danger";
    header("Location: ../cadastro.php");
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $usuario = new Usuario($conn);

    if ($usuario->emailExiste($email)) {
        $_SESSION["mensagem"] = "Este e-mail já está cadastrado.";
        $_SESSION["tipo_mensagem"] = "warning";
        header("Location: ../cadastro.php");
        exit;
    }

    $usuario->cadastrar($nome, $email, $senha);

    $_SESSION["mensagem"] = "Usuário cadastrado com sucesso!";
    $_SESSION["tipo_mensagem"] = "success";

    header("Location: ../cadastro.php");
    exit;

} catch (PDOException $e) {
    $_SESSION["mensagem"] = "Erro ao cadastrar usuário: " . $e->getMessage();
    $_SESSION["tipo_mensagem"] = "danger";

    header("Location: ../cadastro.php");
    exit;
}