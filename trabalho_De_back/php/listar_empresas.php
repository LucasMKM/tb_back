<?php
$titulo = "Empresas";
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/nav.php';

$busca = isset($_GET['busca']) ? mysqli_real_escape_string($con, $_GET['busca']) : '';

$colunas_permitidas = ['empresa_id', 'nome', 'natureza_juridica'];
$ordem = isset($_GET['ordem']) && in_array($_GET['ordem'], $colunas_permitidas) ? $_GET['ordem'] : 'empresa_id';
$direcao = isset($_GET['dir']) && $_GET['dir'] === 'DESC' ? 'DESC' : 'ASC';
$proxima_dir = $direcao === 'ASC' ? 'DESC' : 'ASC';

$sql = "SELECT * FROM empresas WHERE nome LIKE '%$busca%' ORDER BY $ordem $direcao";
$resultado = mysqli_query($con, $sql);
?>

<div class="container">
    <div class="page-header">
        <h1>Empresas <span>·</span></h1>
        <a href="/TRABALHO_DE_BACK/php/empresa.php" class="btn btn-primary">+ Nova Empresa</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <!-- Busca -->
    <form method="GET" class="search-bar">
        <input type="text" name="busca" placeholder="Buscar empresa por nome..." value="<?= htmlspecialchars($busca) ?>">
        <input type="hidden" name="ordem" value="<?= $ordem ?>">
        <input type="hidden" name="dir" value="<?= $direcao ?>">
        <button class="btn btn-secondary" type="submit">Buscar</button>
        <?php if ($busca): ?>
            <a href="listar_empresas.php" class="btn btn-secondary">Limpar</a>
        <?php endif; ?>
    </form>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <?php
                    $cols = [
                        'empresa_id'       => '#ID',
                        'nome'             => 'Nome',
                        'natureza_juridica'=> 'Natureza Jurídica',
                    ];
                    foreach ($cols as $col => $label):
                        $icon = $ordem === $col ? ($direcao === 'ASC' ? '↑' : '↓') : '↕';
                    ?>
                    <th>
                        <a href="?busca=<?= urlencode($busca) ?>&ordem=<?= $col ?>&dir=<?= $ordem === $col ? $proxima_dir : 'ASC' ?>"
                           style="color:inherit;text-decoration:none;">
                            <?= $label ?> <span class="sort-icon"><?= $icon ?></span>
                        </a>
                    </th>
                    <?php endforeach; ?>
                    <th>Cód.</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><span class="badge">#<?= $row['empresa_id'] ?></span></td>
                    <td><strong><?= htmlspecialchars($row['nome']) ?></strong></td>
                    <td><span class="badge badge-green"><?= htmlspecialchars($row['natureza_juridica']) ?></span></td>
                    <td><?= htmlspecialchars($row['endereço'] ?? '') ?></td>
                    <td><code style="color:var(--accent2);font-family:'Space Mono',monospace"><?= htmlspecialchars($row['codigo_secreto']) ?></code></td>
                    <td style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                        <a href="/TRABALHO_DE_BACK/php/editar_empresa.php?id=<?= $row['empresa_id'] ?>"
                           class="btn btn-secondary btn-sm">✏️ Editar</a>
                        <a href="deletar_empresa.php?id=<?= $row['empresa_id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Excluir esta empresa?')">🗑 Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <strong>Nenhuma empresa encontrada</strong>
                            <p><?= $busca ? "Tente outro nome." : "Cadastre a primeira empresa!" ?></p>
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