<?php
$titulo = "Vínculos Cliente-Empresa";
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/nav.php';

// Busca por nome de cliente
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($con, $_GET['busca']) : '';

// Ordenação
$colunas_permitidas = ['c.nome', 'e.nome', 'e.natureza_juridica'];
$ordem = isset($_GET['ordem']) && in_array($_GET['ordem'], $colunas_permitidas) ? $_GET['ordem'] : 'c.nome';
$direcao = isset($_GET['dir']) && $_GET['dir'] === 'DESC' ? 'DESC' : 'ASC';
$proxima_dir = $direcao === 'ASC' ? 'DESC' : 'ASC';

// JOIN principal
$sql = "SELECT
            c.cliente_id,
            c.nome AS cliente_nome,
            c.email AS cliente_email,
            e.empresa_id,
            e.nome AS empresa_nome,
            e.natureza_juridica,
            e.endereco
        FROM dono_empresa de
        INNER JOIN clientes c ON de.cliente_id = c.cliente_id
        INNER JOIN empresas e ON de.empresa_id = e.empresa_id
        WHERE c.nome LIKE '%$busca%'
        ORDER BY $ordem $direcao";

$resultado = mysqli_query($con, $sql);

// Para o form de vínculo: lista todos os clientes e empresas
$clientes_select = mysqli_query($con, "SELECT cliente_id, nome FROM clientes ORDER BY nome");
$empresas_select = mysqli_query($con, "SELECT empresa_id, nome FROM empresas ORDER BY nome");
?>

<div class="container">
    <div class="page-header">
        <h1>Vínculos <span>·</span> Cliente &amp; Empresa</h1>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <!-- Form para novo vínculo -->
    <div class="form-card" style="margin-bottom:2rem;">
        <h2 style="font-size:1.1rem;font-weight:800;margin-bottom:1.2rem;">Criar Novo Vínculo</h2>
        <form method="POST" action="vincular_cliente_empresa.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="cliente_id">Cliente</label>
                    <select name="cliente_id" id="cliente_id" required>
                        <option value="">-- Selecione um cliente --</option>
                        <?php while ($c = mysqli_fetch_assoc($clientes_select)): ?>
                            <option value="<?= $c['cliente_id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="empresa_id">Empresa</label>
                    <select name="empresa_id" id="empresa_id" required>
                        <option value="">-- Selecione uma empresa --</option>
                        <?php while ($e = mysqli_fetch_assoc($empresas_select)): ?>
                            <option value="<?= $e['empresa_id'] ?>"><?= htmlspecialchars($e['nome']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Vincular</button>
        </form>
    </div>

    <!-- Busca -->
    <form method="GET" class="search-bar">
        <input type="text" name="busca" placeholder="Buscar por nome do cliente..." value="<?= htmlspecialchars($busca) ?>">
        <input type="hidden" name="ordem" value="<?= $ordem ?>">
        <input type="hidden" name="dir" value="<?= $direcao ?>">
        <button class="btn btn-secondary" type="submit">Buscar</button>
        <?php if ($busca): ?>
            <a href="join_cliente_empresa.php" class="btn btn-secondary">Limpar</a>
        <?php endif; ?>
    </form>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <?php
                    $cols = [
                        'c.nome'             => 'Cliente',
                        'e.nome'             => 'Empresa',
                        'e.natureza_juridica'=> 'Natureza Jurídica',
                    ];
                    foreach ($cols as $col => $label):
                        $icon = $ordem === $col ? ($direcao === 'ASC' ? '↑' : '↓') : '↕';
                    ?>
                    <th>
                        <a href="?busca=<?= urlencode($busca) ?>&ordem=<?= urlencode($col) ?>&dir=<?= $ordem === $col ? $proxima_dir : 'ASC' ?>"
                           style="color:inherit;text-decoration:none;">
                            <?= $label ?> <span class="sort-icon"><?= $icon ?></span>
                        </a>
                    </th>
                    <?php endforeach; ?>
                    <th>E-mail do Cliente</th>
                    <th>Endereço</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                <tr class="join-row">
                    <td><?= htmlspecialchars($row['cliente_nome']) ?></td>
                    <td><strong><?= htmlspecialchars($row['empresa_nome']) ?></strong></td>
                    <td><span class="badge badge-green"><?= htmlspecialchars($row['natureza_juridica']) ?></span></td>
                    <td><?= htmlspecialchars($row['cliente_email']) ?></td>
                    <td style="font-size:0.82rem;color:var(--muted)"><?= htmlspecialchars($row['endereco']) ?></td>
                    <td>
                        <a href="desvincular_cliente_empresa.php?cliente_id=<?= $row['cliente_id'] ?>&empresa_id=<?= $row['empresa_id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Desvincular este cliente desta empresa?')">🔓 Desvincular</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <strong>Nenhum vínculo encontrado</strong>
                            <p>Use o formulário acima para criar um vínculo.</p>
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