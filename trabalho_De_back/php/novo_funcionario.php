<?php
$titulo = "Novo Funcionário";
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html?erro=Faça+login+para+continuar");
    exit;
}

$empresa_id = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : 0;

if ($empresa_id <= 0) {
    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?erro=Empresa+inválida");
    exit;
}

// Verifica se a empresa pertence ao cliente logado
$cliente_id = $_SESSION['cliente_id'];
$check = mysqli_query($con, "SELECT e.nome FROM empresas e
    INNER JOIN dono_empresa de ON e.empresa_id = de.empresa_id
    WHERE de.cliente_id = $cliente_id AND e.empresa_id = $empresa_id");

if (mysqli_num_rows($check) === 0) {
    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?erro=Acesso+negado");
    exit;
}

$empresa = mysqli_fetch_assoc($check);
$cargos  = mysqli_query($con, "SELECT * FROM cargos ORDER BY nome");
?>

<div class="container">
    <div class="page-header">
        <h1>Novo <span>Funcionário</span></h1>
        <a href="funcionarios.php?empresa_id=<?= $empresa_id ?>" class="btn btn-secondary">← Voltar</a>
    </div>

    <p style="color:var(--muted);margin-bottom:1.5rem;margin-top:-1rem;">
        Empresa: <strong style="color:var(--text)"><?= htmlspecialchars($empresa['nome']) ?></strong>
    </p>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form action="/TRABALHO_DE_BACK/php/salvar_funcionario.php" method="post">
            <input type="hidden" name="empresa_id" value="<?= $empresa_id ?>">
            <div class="form-row">
                <div class="form-group">
                    <label>Nome completo</label>
                    <input type="text" name="nome" placeholder="Ex: Maria Silva" required>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="email@exemplo.com" required>
                </div>
            </div>
            <div class="form-group">
                <label>Cargo</label>
                <select name="cargo_id">
                    <option value="">-- Sem cargo definido --</option>
                    <?php while ($cargo = mysqli_fetch_assoc($cargos)): ?>
                        <option value="<?= $cargo['cargo_id'] ?>">
                            <?= htmlspecialchars($cargo['nome']) ?> — R$ <?= number_format($cargo['salario'], 2, ',', '.') ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div style="display:flex;gap:0.8rem;">
                <button type="submit" class="btn btn-primary">Adicionar Funcionário</button>
                <a href="funcionarios.php?empresa_id=<?= $empresa_id ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>