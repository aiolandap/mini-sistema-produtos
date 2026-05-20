<?php
session_start();

$mensagem = $_SESSION['mensagem'] ?? null;
$tipoMensagem = $_SESSION['tipo_mensagem'] ?? null;

unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Login</h4>
                </div>

                <div class="card-body">

                    <?php if ($mensagem): ?>
                        <div class="alert alert-<?= $tipoMensagem ?>">
                            <?= $mensagem ?>
                        </div>
                    <?php endif; ?>

                    <form action="actions/login_action.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Entrar
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="cadastro.php">Criar uma conta</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>