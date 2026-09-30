<?php
require 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categoria']);
    $quantidade = (int) $_POST['quantidade'];
    $estoque_minimo = (int) $_POST['estoque_minimo'];
    $preco = (float) str_replace(',', '.', $_POST['preco']);

    if ($nome !== '') {
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, categoria, quantidade, estoque_minimo, preco) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $categoria, $quantidade, $estoque_minimo, $preco]);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container container-form">
        <h1>Cadastrar Produto</h1>
        <form method="POST">
            <label>Nome do produto</label>
            <input type="text" name="nome" required>

            <label>Categoria</label>
            <input type="text" name="categoria">

            <label>Quantidade inicial</label>
            <input type="number" name="quantidade" value="0" min="0" required>

            <label>Estoque mínimo (alerta)</label>
            <input type="number" name="estoque_minimo" value="5" min="0" required>

            <label>Preço (R$)</label>
            <input type="text" name="preco" placeholder="0,00" required>

            <button type="submit">Cadastrar</button>
            <a href="index.php" class="link-voltar">Voltar</a>
        </form>
    </div>
    <script src="js/script.js"></script>
</body>
</html>