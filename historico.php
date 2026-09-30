<?php
require 'config/database.php';

$sql = "SELECT m.*, p.nome AS produto_nome 
        FROM movimentacoes m 
        JOIN produtos p ON m.produto_id = p.id 
        ORDER BY m.data_movimentacao DESC";
$stmt = $pdo->query($sql);
$movimentacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Movimentações</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Histórico de Movimentações</h1>
        <a href="index.php" class="link-voltar">← Voltar para o estoque</a>

        <table>
            <tr>
                <th>Produto</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Data</th>
            </tr>
            <?php foreach ($movimentacoes as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['produto_nome']) ?></td>
                    <td>
                        <?php if ($m['tipo'] === 'entrada'): ?>
                            <span class="badge badge-ok">↑ Entrada</span>
                        <?php else: ?>
                            <span class="badge badge-alerta">↓ Saída</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $m['quantidade'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($m['data_movimentacao'])) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($movimentacoes)): ?>
                <tr><td colspan="4" style="text-align:center;">Nenhuma movimentação registrada.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>