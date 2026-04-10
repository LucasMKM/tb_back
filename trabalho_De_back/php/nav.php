<?php
session_start();
$pagina_atual = basename($_SERVER['PHP_SELF']);
function nav_ativo($arquivo, $atual) {
    return $arquivo === $atual ? 'active' : '';
}
$eh_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) : 'Gerenciamento' ?></title>
    <link rel="stylesheet" href="/TRABALHO_DE_BACK/css/login.css">
</head>
<body>
<nav>
    <a class="logo" href="/TRABALHO_DE_BACK/php/minhas_empresas.php">⬡ GestãoPro</a>
    <ul class="nav-links">
        <li><a href="/TRABALHO_DE_BACK/php/listar_clientes.php" class="<?= nav_ativo('listar_clientes.php', $pagina_atual) ?>">👤 Clientes</a></li>
        <li><a href="/TRABALHO_DE_BACK/php/minhas_empresas.php" class="<?= nav_ativo('minhas_empresas.php', $pagina_atual) ?>">🏢 Minhas Empresas</a></li>
        <?php if (isset($_SESSION['cliente_id'])): ?>
        <li><a href="/TRABALHO_DE_BACK/php/logout.php" style="color:var(--danger)">Sair</a></li>
        <?php endif; ?>
    </ul>
</nav>