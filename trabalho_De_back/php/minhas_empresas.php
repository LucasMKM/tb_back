<?php
$titulo = "Minhas Empresas";
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/conexao.php';

// Protege a página — redireciona se não estiver logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html?erro=Faça+login+para+continuar");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$nome_cliente = $_SESSION['nome'];

// Busca empresas vinculadas a este cliente via dono_empresa
$sql = "SELECT e.empresa_id, e.nome, e.natureza_juridica, e.endereco, e.codigo_secreto
        FROM empresas e
        INNER JOIN dono_empresa de ON e.empresa_id = de.empresa_id
        WHERE de.cliente_id = $cliente_id
        ORDER BY e.nome";

$resultado = mysqli_query($con, $sql);
?>

<div class="container">
    <div class="page-header">
        <h1>Olá, <span><?= htmlspecialchars($nome_cliente) ?></span> 👋</h1>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.5px;">
            🏢 Suas Empresas
        </h2>
        <a href="/TRABALHO_DE_BACK/php/empresas.php" class="btn btn-primary">+ Nova Empresa</a>
    </div>

    <?php if (mysqli_num_rows($resultado) > 0): ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;">
        <?php while ($empresa = mysqli_fetch_assoc($resultado)): ?>
            <div class="card" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
                    <div>
                        <h3 style="font-size:1.1rem;font-weight:800;margin-bottom:0.3rem;">
                            <?= htmlspecialchars($empresa['nome']) ?>
                        </h3>
                        <span class="badge badge-green"><?= htmlspecialchars($empresa['natureza_juridica']) ?></span>
                    </div>
                    <code style="color:var(--accent2);font-family:'Space Mono',monospace;font-size:1.1rem;">
                        <?= htmlspecialchars($empresa['codigo_secreto']) ?>
                    </code>
                </div>
                <p style="font-size:0.85rem;color:var(--muted);margin-bottom:1.2rem;">
                    📍 <?= htmlspecialchars($empresa['endereco'] ?? '') ?>
                </p>
                <a href="funcionarios.php?empresa_id=<?= $empresa['empresa_id'] ?>"
                   class="btn btn-primary" style="width:100%;justify-content:center;">
                    👥 Ver Funcionários
                </a>
            </div>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="empty-state">
                <strong>Você ainda não tem empresas</strong>
                <p>Clique em "+ Nova Empresa" para cadastrar a sua.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>