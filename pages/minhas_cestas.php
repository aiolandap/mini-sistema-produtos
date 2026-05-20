<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../classes/Cesta.php";

$nomeUsuario = $_SESSION["usuario_nome"] ?? "Usuário";
$usuarioId = (int) $_SESSION["usuario_id"];

try {
    $database = new Database();
    $conn = $database->getConnection();

    $cestaModel = new Cesta($conn);
    $cestas = $cestaModel->listarPorUsuario($usuarioId);

} catch (Throwable $e) {
    echo "Erro ao carregar minhas cestas: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Cestas</title>

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
        <h3>Minhas Cestas</h3>

        <a href="dashboard.php" class="btn btn-secondary">
            Voltar
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <strong>Cestas criadas</strong>
        </div>

        <div class="card-body">

            <?php if (empty($cestas)): ?>

                <div class="alert alert-info">
                    Nenhuma cesta criada ainda.
                </div>

            <?php else: ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Cesta</th>
                                <th>Data</th>
                                <th>Total de produtos</th>
                                <th>Valor total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($cestas as $cesta): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($cesta["id"]) ?></td>
                                    <td><?= htmlspecialchars($cesta["criado_em"]) ?></td>
                                    <td><?= htmlspecialchars($cesta["total_produtos"]) ?></td>
                                    <td>
                                        R$ <?= number_format((float) $cesta["valor_total"], 2, ",", ".") ?>
                                    </td>
                                    <td>
                                        <a 
                                            href="cesta.php?cesta_id=<?= $cesta["id"] ?>" 
                                            class="btn btn-sm btn-primary"
                                        >
                                            Ver Cesta
                                        </a>

                                        <a 
                                            href="selecionar_produtos.php?cesta_id=<?= $cesta["id"] ?>" 
                                            class="btn btn-sm"
                                            style="background-color: #b76fd1; border-color: #b76fd1; color: #ffffff;"
                                        >
                                            Adicionar Produtos
                                        </a>
                                        <form 
                                            action="../actions/excluir_cesta_action.php" 
                                            method="POST" 
                                            style="display:inline;"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta cesta?');"
                                        >
                                            <input type="hidden" name="cesta_id" value="<?= $cesta["id"] ?>">

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>