<?php
require_once __DIR__ . '/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: listar_clientes.php?erro=ID+inválido");
    exit;
}

// Remove vínculos antes de deletar o cliente
mysqli_query($con, "DELETE FROM dono_empresa WHERE cliente_id = $id");

$sql = "DELETE FROM clientes WHERE cliente_id = $id";

if (mysqli_query($con, $sql)) {
    header("Location: listar_clientes.php?msg=Cliente+removido+com+sucesso!");
} else {
    header("Location: listar_clientes.php?erro=Erro+ao+remover+cliente");
}
exit;
?>