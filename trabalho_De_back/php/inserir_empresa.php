<?php
session_start();
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html?erro=Faça+login+para+continuar");
    exit;
}

$cliente_id        = $_SESSION['cliente_id'];
$nome              = htmlspecialchars(trim($_POST['nome']));
$natureza_juridica = htmlspecialchars(trim($_POST['natureza_juridica']));
$codigo_secreto    = htmlspecialchars(trim($_POST['codigo_secreto']));
$endereco          = htmlspecialchars(trim($_POST['endereco']));

if (empty($nome) || empty($natureza_juridica) || empty($codigo_secreto) || empty($endereco)) {
    header("Location: /TRABALHO_DE_BACK/php/empresa.php?erro=Preencha+todos+os+campos");
    exit;
}

if (strlen($codigo_secreto) !== 3) {
    header("Location: /TRABALHO_DE_BACK/php/empresa.php?erro=Código+secreto+deve+ter+exatamente+3+caracteres");
    exit;
}

$sql = "INSERT INTO empresas (nome, natureza_juridica, codigo_secreto, endereco)
        VALUES ('$nome', '$natureza_juridica', '$codigo_secreto', '$endereco')";

if (mysqli_query($con, $sql)) {
    // Pega o ID da empresa recém-criada e vincula ao cliente logado
    $empresa_id = mysqli_insert_id($con);
    mysqli_query($con, "INSERT INTO dono_empresa (cliente_id, empresa_id) VALUES ($cliente_id, $empresa_id)");

    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?msg=Empresa+cadastrada+com+sucesso!");
} else {
    header("Location: /TRABALHO_DE_BACK/php/empresa.php?erro=" . urlencode("Erro: " . mysqli_error($con)));
}
exit;
?>