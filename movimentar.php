<?php
require 'config/database.php';

$id = $_GET['id'] ?? $_POST['produto_id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = $_POST['tipo'];
    $quantidade = (int) $_POST['quantidade'];

    if ($quantidade > 0) {
        $stmt = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tipo === 'saida' && $quantidade > $produto['quantidade']) {
            $erro = "Quantidade insuficiente em estoque (disponível: {$produto['quantidade']}).";
        } else {
            // Atualiza a quantidade do produto
            if ($tipo === 'entrada') {
                $pdo->prepare("UPDATE produtos SET quantidade = quantidade + ? WHERE id = ?")->execute([$quantidade, $id]);
            } else {
                $pdo->prepare("UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?")->execute([$quantidade, $id]);
            }

            // Registra a movimentação
            $stmt = $pdo->prepare("INSERT INTO movimentacoes (produto_id, tipo, quantidade) VALUES (?, ?, ?)");
            $stmt->execute([$id, $tipo, $quantidade]);

            header("Location: index.php");
            exit;
        }
    } else {
        $erro = "Informe uma quantidade válida.";
    }
}

$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Movimentar Estoque</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container container-form">
        <h1>Movimentar Estoque</h1>
        <p class="info">Produto: <strong><?= htmlspecialchars($produto['nome']) ?></strong> — Quantidade atual: <strong><?= $produto['quantidade'] ?></strong></p>

        <?php if ($erro): ?>
            <div class="msg-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

            <label>Tipo de movimentação</label>
            <select name="tipo" required>
                <option value="entrada">Entrada (adicionar)</option>
                <option value="saida">Saída (retirar)</option>
            </select>

            <label>Quantidade</label>
            <input type="number" name="quantidade" min="1" required>

            <button type="submit">Confirmar</button>
            <a href="index.php" class="link-voltar">Voltar</a>
        </form>
    </div>
<script src="js/script.js"></script>
</body>
</html>