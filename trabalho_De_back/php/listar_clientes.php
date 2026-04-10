<?php
$titulo = "Clientes";
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/nav.php';

// Busca por nome
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($con, $_GET['busca']) : '';

// Ordenação
$colunas_permitidas = ['cliente_id', 'nome', 'email'];
$ordem = isset($_GET['ordem']) && in_array($_GET['ordem'], $colunas_permitidas) ? $_GET['ordem'] : 'cliente_id';
$direcao = isset($_GET['dir']) && $_GET['dir'] === 'DESC' ? 'DESC' : 'ASC';
$proxima_dir = $direcao === 'ASC' ? 'DESC' : 'ASC';

$sql = "SELECT * FROM clientes WHERE nome LIKE '%$busca%' ORDER BY $ordem $direcao";
$resultado = mysqli_query($con, $sql);
?>

<div class="container">
    <div class="page-header">
        <h1>Clientes <span>·</span></h1>
        <?php if ($eh_admin): ?>
            <a href="/TRABALHO_DE_BACK/php/cliente.php" class="btn btn-primary">+ Novo Cliente</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <!-- Busca por nome -->
    <form method="GET" class="search-bar">
        <input type="text" name="busca" placeholder="Buscar cliente por nome..." value="<?= htmlspecialchars($busca) ?>">
        <input type="hidden" name="ordem" value="<?= $ordem ?>">
        <input type="hidden" name="dir" value="<?= $direcao ?>">
        <button class="btn btn-secondary" type="submit">Buscar</button>
        <?php if ($busca): ?>
            <a href="listar_clientes.php" class="btn btn-secondary">Limpar</a>
        <?php endif; ?>
    </form>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <?php
                    $cols = ['cliente_id' => '#ID', 'nome' => 'Nome', 'email' => 'E-mail'];
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
                    <?php if ($eh_admin): ?>
                    <th>Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><span class="badge">#<?= $row['cliente_id'] ?></span></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <?php if ($eh_admin): ?>
                    <td style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                        <a href="editar_cliente.php?id=<?= $row['cliente_id'] ?>"
                           class="btn btn-secondary btn-sm">✏️ Editar</a>
                        <a href="deletar_cliente.php?id=<?= $row['cliente_id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir este cliente?')">🗑 Excluir</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <strong>Nenhum cliente encontrado</strong>
                            <p><?= $busca ? "Tente outro termo de busca." : "Cadastre o primeiro cliente!" ?></p>
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