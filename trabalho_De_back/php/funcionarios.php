<?php
$titulo = "Funcionários";
require_once __DIR__ . '/nav.php';
require_once __DIR__ . '/conexao.php';

// Protege a página
if (!isset($_SESSION['cliente_id'])) {
    header("Location: /TRABALHO_DE_BACK/html/login.html?erro=Faça+login+para+continuar");
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$empresa_id = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : 0;

if ($empresa_id <= 0) {
    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?erro=Empresa+inválida");
    exit;
}

// Verifica se esta empresa pertence ao cliente logado
$check = mysqli_query($con, "SELECT e.nome FROM empresas e
    INNER JOIN dono_empresa de ON e.empresa_id = de.empresa_id
    WHERE de.cliente_id = $cliente_id AND e.empresa_id = $empresa_id");

if (mysqli_num_rows($check) === 0) {
    header("Location: /TRABALHO_DE_BACK/php/minhas_empresas.php?erro=Acesso+negado");
    exit;
}

$empresa = mysqli_fetch_assoc($check);

// Busca por nome
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($con, $_GET['busca']) : '';

// Ordenação
$colunas_permitidas = ['f.nome', 'f.email', 'c.nome'];
$ordem = isset($_GET['ordem']) && in_array($_GET['ordem'], $colunas_permitidas) ? $_GET['ordem'] : 'f.nome';
$direcao = isset($_GET['dir']) && $_GET['dir'] === 'DESC' ? 'DESC' : 'ASC';
$proxima_dir = $direcao === 'ASC' ? 'DESC' : 'ASC';

// Lista funcionários com JOIN no cargo
$sql = "SELECT f.funcionario_id, f.nome, f.email, c.nome AS cargo_nome, c.salario
        FROM funcionarios f
        LEFT JOIN cargos c ON f.cargo_id = c.cargo_id
        WHERE f.empresa_id = $empresa_id AND f.nome LIKE '%$busca%'
        ORDER BY $ordem $direcao";

$resultado = mysqli_query($con, $sql);
?>

<div class="container">
    <div class="page-header">
        <h1>Funcionários <span>· <?= htmlspecialchars($empresa['nome']) ?></span></h1>
        <div style="display:flex;gap:0.5rem;">
            <a href="novo_funcionario.php?empresa_id=<?= $empresa_id ?>" class="btn btn-primary">+ Novo Funcionário</a>
            <a href="minhas_empresas.php" class="btn btn-secondary">← Voltar</a>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <!-- Busca -->
    <form method="GET" class="search-bar">
        <input type="hidden" name="empresa_id" value="<?= $empresa_id ?>">
        <input type="text" name="busca" placeholder="Buscar funcionário por nome..."
               value="<?= htmlspecialchars($busca) ?>">
        <input type="hidden" name="ordem" value="<?= $ordem ?>">
        <input type="hidden" name="dir" value="<?= $direcao ?>">
        <button class="btn btn-secondary" type="submit">Buscar</button>
        <?php if ($busca): ?>
            <a href="funcionarios.php?empresa_id=<?= $empresa_id ?>" class="btn btn-secondary">Limpar</a>
        <?php endif; ?>
    </form>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <?php
                    $cols = ['f.nome' => 'Nome', 'f.email' => 'E-mail', 'c.nome' => 'Cargo'];
                    foreach ($cols as $col => $label):
                        $icon = $ordem === $col ? ($direcao === 'ASC' ? '↑' : '↓') : '↕';
                    ?>
                    <th>
                        <a href="?empresa_id=<?= $empresa_id ?>&busca=<?= urlencode($busca) ?>&ordem=<?= urlencode($col) ?>&dir=<?= $ordem === $col ? $proxima_dir : 'ASC' ?>"
                           style="color:inherit;text-decoration:none;">
                            <?= $label ?> <span class="sort-icon"><?= $icon ?></span>
                        </a>
                    </th>
                    <?php endforeach; ?>
                    <th>Salário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nome']) ?></strong></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <?php if ($row['cargo_nome']): ?>
                            <span class="badge badge-green"><?= htmlspecialchars($row['cargo_nome']) ?></span>
                        <?php else: ?>
                            <span class="badge">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['salario']): ?>
                            R$ <?= number_format($row['salario'], 2, ',', '.') ?>
                        <?php else: ?>
                            <span style="color:var(--muted)">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="display:flex;gap:0.4rem;">
                        <a href="editar_funcionario.php?id=<?= $row['funcionario_id'] ?>&empresa_id=<?= $empresa_id ?>"
                           class="btn btn-secondary btn-sm">✏️ Editar</a>
                        <a href="deletar_funcionario.php?id=<?= $row['funcionario_id'] ?>&empresa_id=<?= $empresa_id ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Remover este funcionário?')">🗑 Remover</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <strong>Nenhum funcionário cadastrado</strong>
                            <p>Use o formulário acima para adicionar.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>