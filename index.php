<?php

require_once __DIR__ . "/config/database.php";

try {
    $database = new Database();
    $pdo = $database->getConnection();

    echo "<h1>Projeto iniciado com sucesso!</h1>";
    echo "<p>Banco de dados e tabelas criados corretamente.</p>";
} catch (PDOException $e) {
    echo "<h1>Erro ao conectar com o banco</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}