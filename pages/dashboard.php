<?php

require_once __DIR__ . "/../includes/auth.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

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
    <div class="card shadow">
        <div class="card-body">
            <h3>Dashboard</h3>
            <p>Bem-vindo ao Mini Sistema de Gestão de Produtos.</p>

            <div class="row mt-4">
                <div class="col-md-3 mb-3">
                    <a href="fornecedores.php" class="btn btn-primary w-100">
                        Fornecedores
                    </a>
                </div>

                <div class="col-md-3 mb-3">
                    <a href="produtos.php" class="btn btn-success w-100">
                        Produtos
                    </a>
                </div>

                <div class="col-md-3 mb-3">
                    <a 
                        href="criar_cesta.php" 
                        class="btn w-100"
                        style="background-color: #b76fd1; border-color: #b76fd1; color: #ffffff;"
                    >
                        Criar Cesta
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                <a 
                    href="minhas_cestas.php" 
                    class="btn w-100"
                    style="background-color: #ffc107; border-color: #ffc107; color: #000000;"
                >
                    Minhas Cestas
                </a>
            </div>
            </div>

            
                    

        </div>
    </div>
</div>

</body>
</html>