<?php
require 'config/database.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categoria']);
    $estoque_minimo = (int) $_POST['estoque_minimo'];
    $preco = (float) str_replace(',', '.', $_POST['preco']);

    $stmt = $pdo->prepare("UPDATE produtos SET nome=?, categoria=?, estoque_minimo=?, preco=? WHERE id=?");
    $stmt->execute([$nome, $categoria, $estoque_minimo, $preco, $id]);
    header("Location: index.php");
    exit;
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
    <title>Editar Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container container-form">
        <h1>Editar Produto</h1>
        <form method="POST">
            <label>Nome do produto</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

            <label>Categoria</label>
            <input type="text" name="categoria" value="<?= htmlspecialchars($produto['categoria']) ?>">

            <label>Estoque mínimo (alerta)</label>
            <input type="number" name="estoque_minimo" value="<?= $produto['estoque_minimo'] ?>" min="0" required>

            <label>Preço (R$)</label>
            <input type="text" name="preco" value="<?= number_format($produto['preco'], 2, ',', '') ?>" required>

            <p class="info">Quantidade atual: <strong><?= $produto['quantidade'] ?></strong> (use "Movimentar" para alterar)</p>

            <button type="submit">Salvar Alterações</button>
            <a href="index.php" class="link-voltar">Voltar</a>
        </form>
    </div>
</body>
</html>