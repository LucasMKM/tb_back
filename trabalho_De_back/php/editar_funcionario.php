<?php
$titulo = "Editar Funcionário";
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html");
    exit;
}

$id         = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$empresa_id = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : 0;

$res = mysqli_query($con, "SELECT * FROM funcionarios WHERE funcionario_id = $id AND empresa_id = $empresa_id");
$func = mysqli_fetch_assoc($res);

if (!$func) {
    header("Location: /TRABALHO_DE_BACK/php/funcionarios.php?empresa_id=$empresa_id&erro=Funcionário+não+encontrado");
    exit;
}

$cargos = mysqli_query($con, "SELECT * FROM cargos ORDER BY nome");
?>

<div class="container">
    <div class="page-header">
        <h1>Editar <span>Funcionário</span></h1>
        <a href="funcionarios.php?empresa_id=<?= $empresa_id ?>" class="btn btn-secondary">← Voltar</a>
    </div>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form action="/TRABALHO_DE_BACK/php/atualizar_funcionario.php" method="post">
            <input type="hidden" name="id" value="<?= $func['funcionario_id'] ?>">
            <input type="hidden" name="empresa_id" value="<?= $empresa_id ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($func['nome']) ?>" required>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($func['email']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Cargo</label>
                <select name="cargo_id">
                    <option value="">-- Sem cargo --</option>
                    <?php while ($cargo = mysqli_fetch_assoc($cargos)): ?>
                        <option value="<?= $cargo['cargo_id'] ?>"
                            <?= $func['cargo_id'] == $cargo['cargo_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cargo['nome']) ?> — R$ <?= number_format($cargo['salario'], 2, ',', '.') ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div style="display:flex;gap:0.8rem;">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="funcionarios.php?empresa_id=<?= $empresa_id ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>