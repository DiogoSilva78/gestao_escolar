<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

// Procurar todos os utilizadores
$query = "SELECT id, nome, email, tipo FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Utilizadores</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

<h2>Gestão de Utilizadores</h2>
<a href="dashboard_admin.php">Voltar</a>
<a href="add_user.php">Adicionar Usuário</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Ações</th>
    </tr>
    <?php while ($user = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['nome']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo $user['tipo']; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Editar</a> |
                <a href="../controllers/delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
