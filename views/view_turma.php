<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: manage_turmas.php");
    exit();
}

$turma_id = $_GET['id'];

// Buscar nome da turma
$turma_query = $conn->prepare("SELECT nome FROM turmas WHERE id = ?");
$turma_query->bind_param("i", $turma_id);
$turma_query->execute();
$turma_result = $turma_query->get_result();
$turma = $turma_result->fetch_assoc();

// Procurar alunos associados a esta turma
$alunos_query = $conn->prepare("
    SELECT users.id, users.nome, users.email 
    FROM alunos 
    JOIN users ON alunos.user_id = users.id 
    WHERE alunos.turma_id = ?
");
$alunos_query->bind_param("i", $turma_id);
$alunos_query->execute();
$alunos_result = $alunos_query->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos da Turma <?php echo htmlspecialchars($turma['nome']); ?></title>
</head>
<body>

<h2>Alunos da Turma: <?php echo htmlspecialchars($turma['nome']); ?></h2>
<a href="manage_turmas.php">Voltar</a>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
    </tr>
    <?php while ($aluno = $alunos_result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $aluno['id']; ?></td>
            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
            <td><?php echo htmlspecialchars($aluno['email']); ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
