<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $query = $conn->prepare("INSERT INTO turmas (nome) VALUES (?)");
    $query->bind_param("s", $nome);

    if ($query->execute()) {
        header("Location: manage_turmas.php");
        exit();
    } else {
        $error = "Erro ao adicionar turma.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Turma</title>
</head>
<body>

<h2>Adicionar Turma</h2>
<a href="manage_turmas.php">Voltar</a>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    Nome da Turma: <input type="text" name="nome" required><br>
    <input type="submit" value="Adicionar">
</form>

</body>
</html>
