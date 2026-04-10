<?php
session_start();
require "conexao.php";

$email = htmlspecialchars(trim($_POST['email']));
$senha = htmlspecialchars(trim($_POST['senha']));

if (empty($email) || empty($senha)) {
    header("Location: /TRABALHO_DE_BACK/html/login.html?erro=Preencha+todos+os+campos");
    exit;
}

$sql = "SELECT * FROM clientes WHERE email='$email' AND senha='$senha'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $cliente = mysqli_fetch_assoc($result);
    $_SESSION['cliente_id'] = $cliente['cliente_id'];
    $_SESSION['nome']       = $cliente['nome'];
    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?msg=Bem-vindo,+" . urlencode($cliente['nome']) . "!");
} else {
    header("Location: /TRABALHO_DE_BACK/html/login.html?erro=E-mail+ou+senha+inválidos");
}
exit;
?>