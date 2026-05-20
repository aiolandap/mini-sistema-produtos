<?php
require_once __DIR__ . "/../includes/auth.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";

$mensagem = $_SESSION["mensagem"] ?? null;
$tipoMensagem = $_SESSION["tipo_mensagem"] ?? null;

unset($_SESSION["mensagem"], $_SESSION["tipo_mensagem"]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Cesta</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Sistema de Produtos</a>

        <div class="navbar-nav ms-auto">
            <span class="navbar-text text-white me-3">
                Olá, <?= htmlspecialchars($nomeUsuario) ?>
            </span>

            <a class="btn btn-outline-light btn-sm" href="../actions/logout_action.php">
                Sair
            </a>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Criar Cesta</h3>

        <a href="dashboard.php" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?= htmlspecialchars($tipoMensagem) ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <h5>Nova cesta de produtos</h5>

            <p>
                Clique no botão abaixo para criar uma nova cesta.
                Depois disso, você poderá selecionar os produtos desejados.
            </p>

            <form action="../actions/criar_cesta_action.php" method="POST">
                <button 
                    type="submit" 
                    class="btn"
                    style="background-color: #b76fd1; border-color: #b76fd1; color: #ffffff;"
                >
                    Criar nova cesta
                </button>
            </form>
        </div>
    </div>

</div>

</body>
</html>