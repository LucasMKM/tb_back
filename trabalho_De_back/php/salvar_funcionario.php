<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html");
    exit;
}

$empresa_id = isset($_POST['empresa_id']) ? (int)$_POST['empresa_id'] : 0;
$nome       = htmlspecialchars(trim($_POST['nome']));
$email      = htmlspecialchars(trim($_POST['email']));
$cargo_id   = !empty($_POST['cargo_id']) ? (int)$_POST['cargo_id'] : 'NULL';

if ($empresa_id <= 0 || empty($nome) || empty($email)) {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&erro=Preencha+nome+e+e-mail");
    exit;
}

$sql = "INSERT INTO funcionarios (nome, email, cargo_id, empresa_id)
        VALUES ('$nome', '$email', $cargo_id, $empresa_id)";

if (mysqli_query($con, $sql)) {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&msg=Funcionário+adicionado!");
} else {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&erro=" . urlencode("Erro: " . mysqli_error($con)));
}
exit;
?>