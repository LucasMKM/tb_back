<?php
require_once __DIR__ . '/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: listar_empresas.php?erro=ID+inválido");
    exit;
}

// Remove vínculos antes de deletar a empresa
mysqli_query($con, "DELETE FROM dono_empresa WHERE empresa_id = $id");
mysqli_query($con, "UPDATE funcionarios SET empresa_id = NULL WHERE empresa_id = $id");

$sql = "DELETE FROM empresas WHERE empresa_id = $id";

if (mysqli_query($con, $sql)) {
    header("Location: listar_empresas.php?msg=Empresa+removida+com+sucesso!");
} else {
    header("Location: listar_empresas.php?erro=Erro+ao+remover+empresa");
}
exit;
?>