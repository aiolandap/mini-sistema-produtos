<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario_id"])) {
    $_SESSION["mensagem"] = "Você precisa fazer login para acessar essa página.";
    $_SESSION["tipo_mensagem"] = "warning";

    header("Location: ../login.php");
    exit;
}