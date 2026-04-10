<?php
require_once __DIR__ . '/conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$resultado = mysqli_query($con, "SELECT cliente_id, nome, email FROM clientes WHERE cliente_id = $id");
$cliente = mysqli_fetch_assoc($resultado);

header('Content-Type: application/json');
echo json_encode($cliente);
?>