<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

$query = "SELECT * FROM turmas";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Turmas</title>
</head>
<body>

<h2>Gestão de Turmas</h2>
<a href="dashboard_admin.php">Voltar</a>
<a href="add_turma.php">Adicionar Turma</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Ações</th>
    </tr>
    <?php while ($turma = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $turma['id']; ?></td>
            <td><?php echo htmlspecialchars($turma['nome']); ?></td>
            <td>
                <a href="view_turma.php?id=<?php echo $turma['id']; ?>">Ver Alunos</a> |
                <a href="edit_turma.php?id=<?php echo $turma['id']; ?>">Editar</a> |
                <a href="../controllers/delete_turma.php?id=<?php echo $turma['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
