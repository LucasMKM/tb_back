<?php
require_once __DIR__ . '/conexao.php';

$nome  = htmlspecialchars(trim($_POST['nome']));
$email = htmlspecialchars(trim($_POST['email']));
$senha = htmlspecialchars(trim($_POST['senha']));

if (empty($nome) || empty($email) || empty($senha)) {
    header("Location: /TRABALHO_DE_BACK/html/cadastro.html?erro=Preencha+todos+os+campos");
    exit;
}

$sql = "INSERT INTO clientes (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

if (mysqli_query($con, $sql)) {
    header("Location: /TRABALHO_DE_BACK/html/login.html?msg=Cadastro+realizado!+Faça+login.");
} else {
    $erro = mysqli_errno($con) == 1062
        ? "E-mail+já+cadastrado"
        : "Erro+ao+cadastrar:+" . urlencode(mysqli_error($con));
    header("Location: /TRABALHO_DE_BACK/html/cadastro.html?erro=$erro");
}
exit;
?>