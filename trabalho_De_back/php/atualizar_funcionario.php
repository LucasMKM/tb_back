<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html");
    exit;
}

$id         = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$empresa_id = isset($_POST['empresa_id']) ? (int)$_POST['empresa_id'] : 0;
$nome       = htmlspecialchars(trim($_POST['nome']));
$email      = htmlspecialchars(trim($_POST['email']));
$cargo_id   = !empty($_POST['cargo_id']) ? (int)$_POST['cargo_id'] : 'NULL';

if ($id <= 0 || empty($nome) || empty($email)) {
    header("Location: /TRABALHO_DE_BACK/php/editar_funcionario.php?id=$id&empresa_id=$empresa_id&erro=Preencha+todos+os+campos");
    exit;
}

$sql = "UPDATE funcionarios SET nome='$nome', email='$email', cargo_id=$cargo_id
        WHERE funcionario_id=$id AND empresa_id=$empresa_id";

if (mysqli_query($con, $sql)) {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&msg=Funcionário+atualizado!");
} else {
    header("Location: /TRABALHO_DE_BACK/php/editar_funcionario.php?id=$id&empresa_id=$empresa_id&erro=Erro+ao+atualizar");
}
exit;
?>