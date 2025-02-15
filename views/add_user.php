<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

// Buscar turmas disponíveis
$turma_query = $conn->query("SELECT * FROM turmas");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $turma_id = ($_POST['turma_id'] != '') ? $_POST['turma_id'] : null;

    // Inserir usuário
    $query = $conn->prepare("INSERT INTO users (nome, email, password, tipo) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssss", $nome, $email, $password, $tipo);

    if ($query->execute()) {
        $user_id = $query->insert_id;

        // Se for aluno, associar à turma
        if ($tipo === 'aluno' && $turma_id) {
            $aluno_query = $conn->prepare("INSERT INTO alunos (user_id, turma_id) VALUES (?, ?)");
            $aluno_query->bind_param("ii", $user_id, $turma_id);
            $aluno_query->execute();
        }

        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Erro ao adicionar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
</head>
<body>

<h2>Adicionar Usuário</h2>
<a href="manage_users.php">Voltar</a>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="password" required><br>
    Tipo:
    <select name="tipo" id="tipo" onchange="toggleTurma()">
        <option value="admin">Administrador</option>
        <option value="funcionario">Funcionário</option>
        <option value="aluno">Aluno</option>
    </select><br>

    <div id="turmaSection" style="display: none;">
        Turma:
        <select name="turma_id">
            <option value="">Sem turma</option>
            <?php while ($turma = $turma_query->fetch_assoc()) { ?>
                <option value="<?php echo $turma['id']; ?>"><?php echo $turma['nome']; ?></option>
            <?php } ?>
        </select>
    </div>

    <input type="submit" value="Adicionar">
</form>

<script>
    function toggleTurma() {
        let tipo = document.getElementById("tipo").value;
        let turmaSection = document.getElementById("turmaSection");
        turmaSection.style.display = (tipo === "aluno") ? "block" : "none";
    }
</script>

</body>
</html>
