<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Cesta.php";
require_once __DIR__ . "/../classes/Produto.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";
$usuarioId = (int) $_SESSION["usuario_id"];
$cestaId = (int) ($_GET["cesta_id"] ?? 0);

$mensagem = $_SESSION["mensagem"] ?? null;
$tipoMensagem = $_SESSION["tipo_mensagem"] ?? null;

unset($_SESSION["mensagem"], $_SESSION["tipo_mensagem"]);

if ($cestaId <= 0) {
    $_SESSION["mensagem"] = "Cesta inválida.";
    $_SESSION["tipo_mensagem"] = "danger";
    header("Location: criar_cesta.php");
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $cestaModel = new Cesta($conn);
    $produtoModel = new Produto($conn);

    $cesta = $cestaModel->buscarPorIdEUsuario($cestaId, $usuarioId);

    if (!$cesta) {
        $_SESSION["mensagem"] = "Cesta não encontrada.";
        $_SESSION["tipo_mensagem"] = "danger";
        header("Location: criar_cesta.php");
        exit;
    }

    $produtos = $produtoModel->listarTodos();

} catch (Throwable $e) {
    echo "Erro ao carregar seleção de produtos: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Selecionar Produtos</title>

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
        <h3>Selecionar Produtos</h3>

        <a href="criar_cesta.php" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?= htmlspecialchars($tipoMensagem) ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <div id="mensagemValidacao"></div>

    <div class="card shadow">
        <div class="card-header">
            <strong>Cesta #<?= $cestaId ?></strong>
        </div>

        <div class="card-body">

            <?php if (empty($produtos)): ?>

                <div class="alert alert-info">
                    Nenhum produto cadastrado ainda.
                </div>

            <?php else: ?>

                <form id="formSelecionarProdutos" action="../actions/adicionar_produtos_cesta_action.php" method="POST">
                    <input type="hidden" name="cesta_id" value="<?= $cestaId ?>">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Selecionar</th>
                                    <th>Produto</th>
                                    <th>Descrição</th>
                                    <th>Preço</th>
                                    <th>Fornecedor</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($produtos as $produto): ?>
                                    <tr>
                                        <td>
                                            <input 
                                                type="checkbox" 
                                                name="produtos[]" 
                                                value="<?= htmlspecialchars($produto["id"]) ?>"
                                                class="form-check-input produto-checkbox"
                                            >
                                        </td>

                                        <td><?= htmlspecialchars($produto["nome"]) ?></td>
                                        <td><?= htmlspecialchars($produto["descricao"] ?? "") ?></td>
                                        <td>
                                            R$ <?= number_format((float) $produto["preco"], 2, ",", ".") ?>
                                        </td>
                                        <td><?= htmlspecialchars($produto["fornecedor_nome"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button 
                        type="submit" 
                        class="btn"
                        style="background-color: #b76fd1; border-color: #b76fd1; color: #ffffff;"
                    >
                        Adicionar à Cesta
                    </button>
                </form>

            <?php endif; ?>

        </div>
    </div>

</div>

<script src="../assets/js/validacao_cesta.js"></script>

</body>
</html>