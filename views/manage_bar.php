<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

// Buscar produtos do bar
$query = "SELECT * FROM produtos WHERE categoria = 'bar'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos - Bar</title>
</head>
<body>

<h2>Gestão de Produtos - Bar</h2>
<a href="dashboard_admin.php">Voltar</a>
<a href="add_produto_bar.php">Adicionar Produto</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço (€)</th>
        <th>Estoque</th>
        <th>Ações</th>
    </tr>
    <?php while ($produto = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $produto['id']; ?></td>
            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
            <td><?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
            <td><?php echo $produto['stock']; ?></td>
            <td>
                <a href="edit_produto_bar.php?id=<?php echo $produto['id']; ?>">Editar</a> |
                <a href="../controllers/delete_produto.php?id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
