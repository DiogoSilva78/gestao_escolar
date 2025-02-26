<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $stock = $_POST['stock'];

    $query = $conn->prepare("INSERT INTO produtos (nome, preco, stock, categoria) VALUES (?, ?, ?, 'papelaria')");
    $query->bind_param("sdi", $nome, $preco, $stock);

    if ($query->execute()) {
        header("Location: manage_papelaria.php");
        exit();
    } else {
        $error = "Erro ao adicionar produto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php
    $pageTitle = "Painel do Administrador"; // Define o título da página dinamicamente
    include '../includes/head.php';
    ?>
</head>

<body>

    <h2>Adicionar Produto - Papelaria</h2>
    <a href="manage_papelaria.php">Voltar</a>

    <?php if (isset($error))
        echo "<p style='color:red;'>$error</p>"; ?>

    <form method="post">
        Nome: <input type="text" name="nome" required><br>
        Preço (€): <input type="number" step="0.01" name="preco" required><br>
        Stock: <input type="number" name="stock" required><br>
        <input type="submit" value="Adicionar">
    </form>

    <?php include '../includes/footer.php'; ?>

</body>

</html>