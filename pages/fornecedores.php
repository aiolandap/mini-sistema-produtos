<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../includes/auth.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Fornecedores</title>

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
        <h3>Cadastro de Fornecedores</h3>

        <a href="dashboard.php" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div id="mensagem"></div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="formFornecedor">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome do fornecedor *</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">CNPJ</label>
                        <input type="text" name="cnpj" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Endereço</label>
                        <input type="text" name="endereco" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Cadastrar Fornecedor
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <strong>Fornecedores cadastrados</strong>
        </div>

        <div class="card-body">
            <div id="listaFornecedores">
                Carregando fornecedores...
            </div>
        </div>
    </div>

</div>

<script src="../assets/js/fornecedores.js"></script>

</body>
</html>