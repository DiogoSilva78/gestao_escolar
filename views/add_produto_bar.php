<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $stock = $_POST['stock'];

    $query = $conn->prepare("INSERT INTO produtos (nome, preco, stock, categoria) VALUES (?, ?, ?, 'bar')");
    $query->bind_param("sdi", $nome, $preco, $stock);

    if ($query->execute()) {
        header("Location: manage_bar.php");
        exit();
    } else {
        $error = "Erro ao adicionar produto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto - Bar</title>
</head>
<body>

<h2>Adicionar Produto - Bar</h2>
<a href="manage_bar.php">Voltar</a>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    Nome: <input type="text" name="nome" required><br>
    Preço (€): <input type="number" step="0.01" name="preco" required><br>
    Estoque: <input type="number" name="stock" required><br>
    <input type="submit" value="Adicionar">
</form>

</body>
</html>
