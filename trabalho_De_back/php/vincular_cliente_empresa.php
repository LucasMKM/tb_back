<?php
require_once __DIR__ . '/conexao.php';

$cliente_id = isset($_POST['cliente_id']) ? (int)$_POST['cliente_id'] : 0;
$empresa_id = isset($_POST['empresa_id']) ? (int)$_POST['empresa_id'] : 0;

if ($cliente_id <= 0 || $empresa_id <= 0) {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?erro=Selecione+cliente+e+empresa");
    exit;
}

$check = mysqli_query($con, "SELECT 1 FROM dono_empresa WHERE cliente_id=$cliente_id AND empresa_id=$empresa_id");
if (mysqli_num_rows($check) > 0) {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?erro=Vínculo+já+existe");
    exit;
}

$sql = "INSERT INTO dono_empresa (cliente_id, empresa_id) VALUES ($cliente_id, $empresa_id)";

if (mysqli_query($con, $sql)) {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?msg=Vínculo+criado+com+sucesso!");
} else {
    header("Location: /TRABALHO_DE_BACK/php/join_cliente_empresa.php?erro=" . urlencode("Erro: " . mysqli_error($con)));
}
exit;
?>