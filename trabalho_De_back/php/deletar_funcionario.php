<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html");
    exit;
}

$id         = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$empresa_id = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : 0;

if ($id <= 0 || $empresa_id <= 0) {
    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?erro=ID+inválido");
    exit;
}

$sql = "DELETE FROM funcionarios WHERE funcionario_id=$id AND empresa_id=$empresa_id";

if (mysqli_query($con, $sql)) {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&msg=Funcionário+removido!");
} else {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&erro=Erro+ao+remover");
}
exit;
?>