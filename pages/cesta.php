<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Cesta.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";
$usuarioId = (int) $_SESSION["usuario_id"];
$cestaId = (int) ($_GET["cesta_id"] ?? 0);

$mensagem = $_SESSION["mensagem"] ?? null;
$tipoMensagem = $_SESSION["tipo_mensagem"] ?? null;

unset($_SESSION["mensagem"], $_SESSION["tipo_mensagem"]);

if ($cestaId <= 0) {
    $_SESSION["mensagem"] = "Cesta inválida.";
    $_SESSION["tipo_mensagem"] = "danger";
    header("Location: minhas_cestas.php");
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
        header("Location: minhas_cestas.php");
        exit;
    }

} catch (Throwable $e) {
    echo "Erro ao carregar cesta: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Cesta</title>

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
        <h3>Minha Cesta</h3>

        <div>
            <a 
                href="selecionar_produtos.php?cesta_id=<?= $cestaId ?>" 
                class="btn"
                style="background-color: #b76fd1; border-color: #b76fd1; color: #ffffff;"
            >
                Adicionar Produtos
            </a>

            <a href="minhas_cestas.php" class="btn btn-secondary">
                Voltar
            </a>
        </div>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?= htmlspecialchars($tipoMensagem) ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <div id="conteudoCesta">
        <div class="alert alert-info">
            Carregando cesta...
        </div>
    </div>

</div>

<script src="../assets/js/cesta.js"></script>

</body>
</html>