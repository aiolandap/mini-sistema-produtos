<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Fornecedor.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";

$database = new Database();
$conn = $database->getConnection();

$fornecedorModel = new Fornecedor($conn);
$fornecedores = $fornecedorModel->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>

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
        <h3>Cadastro de Produtos</h3>

        <a href="dashboard.php" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div id="mensagem"></div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="formProduto">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome do produto *</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Preço *</label>
                        <input type="text" name="preco" class="form-control" placeholder="Ex: 25.90" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Fornecedor *</label>
                        <select name="fornecedor_id" class="form-select" required>
                            <option value="">Selecione um fornecedor</option>

                            <?php foreach ($fornecedores as $fornecedor): ?>
                                <option value="<?= $fornecedor["id"] ?>">
                                    <?= htmlspecialchars($fornecedor["nome"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    Cadastrar Produto
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <strong>Produtos cadastrados</strong>
        </div>

        <div class="card-body">
            <div id="listaProdutos">
                Carregando produtos...
            </div>
        </div>
    </div>

</div>

<script src="../assets/js/produtos.js"></script>

</body>
</html>