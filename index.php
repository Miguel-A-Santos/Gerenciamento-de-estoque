<?php
require 'config/database.php';

$stmt = $pdo->query("SELECT * FROM produtos ORDER BY nome ASC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Controle de Estoque</h1>

        <div class="acoes-topo">
            <a href="cadastrar.php" class="btn">+ Novo Produto</a>
            <a href="historico.php" class="btn btn-secundario">Histórico</a>
        </div>

        <table>
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Quantidade</th>
                <th>Estoque Mínimo</th>
                <th>Preço</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($produtos as $p): ?>
                <?php $baixo = $p['quantidade'] <= $p['estoque_minimo']; ?>
                <tr class="<?= $baixo ? 'linha-alerta' : '' ?>">
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                    <td><?= $p['quantidade'] ?></td>
                    <td><?= $p['estoque_minimo'] ?></td>
                    <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                    <td>
                        <?php if ($baixo): ?>
                            <span class="badge badge-alerta">⚠ Estoque baixo</span>
                        <?php else: ?>
                            <span class="badge badge-ok">OK</span>
                        <?php endif; ?>
                    </td>
                    <td class="acoes">
                        <a href="movimentar.php?id=<?= $p['id'] ?>">Movimentar</a>
                        <a href="editar.php?id=<?= $p['id'] ?>">Editar</a>
                        <a href="excluir.php?id=<?= $p['id'] ?>" onclick="return confirm('Excluir este produto?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($produtos)): ?>
                <tr><td colspan="7" style="text-align:center;">Nenhum produto cadastrado.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>