<?php
require_once __DIR__ . '/conexao.php';

$cliente_id = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
$empresa_id = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : 0;

if ($cliente_id <= 0 || $empresa_id <= 0) {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?erro=IDs+inválidos");
    exit;
}

$sql = "DELETE FROM dono_empresa WHERE cliente_id=$cliente_id AND empresa_id=$empresa_id";

if (mysqli_query($con, $sql)) {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?msg=Desvinculado+com+sucesso!");
} else {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?erro=Erro+ao+desvincular");
}
exit;
?>