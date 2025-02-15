<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$id = $_GET['id'];
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Buscar turmas disponíveis
$turma_query = $conn->query("SELECT * FROM turmas");

// Buscar turma atual do aluno (se for aluno)
$turma_id = null;
if ($user['tipo'] === 'aluno') {
    $turma_query_user = $conn->prepare("SELECT turma_id FROM alunos WHERE user_id = ?");
    $turma_query_user->bind_param("i", $id);
    $turma_query_user->execute();
    $turma_result = $turma_query_user->get_result();
    $turma = $turma_result->fetch_assoc();
    $turma_id = $turma ? $turma['turma_id'] : null;
}

// Processar atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $tipo = $_POST['tipo'];
    $turma_id = isset($_POST['turma_id']) && $_POST['turma_id'] != '' ? $_POST['turma_id'] : null;

    $query = $conn->prepare("UPDATE users SET nome = ?, email = ?, tipo = ? WHERE id = ?");
    $query->bind_param("sssi", $nome, $email, $tipo, $id);

    if ($query->execute()) {
        // Se for aluno, atualizar turma
        if ($tipo === 'aluno') {
            // Verifica se o aluno já tem uma entrada na tabela alunos
            $check_aluno = $conn->prepare("SELECT * FROM alunos WHERE user_id = ?");
            $check_aluno->bind_param("i", $id);
            $check_aluno->execute();
            $result_aluno = $check_aluno->get_result();

            if ($result_aluno->num_rows > 0) {
                // Atualiza a turma do aluno
                $update_turma = $conn->prepare("UPDATE alunos SET turma_id = ? WHERE user_id = ?");
                $update_turma->bind_param("ii", $turma_id, $id);
                $update_turma->execute();
            } else {
                // Insere a turma para o aluno se ainda não existir
                $insert_turma = $conn->prepare("INSERT INTO alunos (user_id, turma_id) VALUES (?, ?)");
                $insert_turma->bind_param("ii", $id, $turma_id);
                $insert_turma->execute();
            }
        }

        header("Location: manage_users.php");
        exit();
    } else {
        echo "Erro ao editar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
</head>
<body>

<h2>Editar Usuário</h2>
<a href="manage_users.php">Voltar</a>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    Nome: <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
    Tipo:
    <select name="tipo">
        <option value="admin" <?php echo ($user['tipo'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
        <option value="funcionario" <?php echo ($user['tipo'] === 'funcionario') ? 'selected' : ''; ?>>Funcionário</option>
        <option value="aluno" <?php echo ($user['tipo'] === 'aluno') ? 'selected' : ''; ?>>Aluno</option>
    </select><br>

    <div id="turmaSection" style="display: <?php echo ($user['tipo'] === 'aluno') ? 'block' : 'none'; ?>;">
        Turma:
        <select name="turma_id">
            <option value="">Sem turma</option>
            <?php while ($turma = $turma_query->fetch_assoc()) { ?>
                <option value="<?php echo $turma['id']; ?>" <?php echo ($turma_id == $turma['id']) ? 'selected' : ''; ?>>
                    <?php echo $turma['nome']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <input type="submit" value="Salvar">
</form>

</body>
</html>
