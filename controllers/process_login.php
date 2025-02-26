<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Buscar o usuário na base de dados
    $query = $conn->prepare("SELECT id, nome, password, tipo FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar se a senha está correta
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['user_type'] = $user['tipo'];

            // Redirecionar para a área correspondente
            if ($user['tipo'] == 'admin') {
                header("Location: ../views/dashboard_admin.php");
            } elseif ($user['tipo'] == 'funcionario') {
                header("Location: ../views/dashboard_funcionario.php");
            } elseif ($user['tipo'] == 'professor') {
                header("Location: ../views/dashboard_professor.php");
            } elseif ($user['tipo'] == 'aluno') {
                header("Location: ../views/dashboard_alunos.php");
            }
            exit();
        } else {
            header("Location: ../index.php?error=Senha incorreta!");
            exit();
        }
    } else {
        header("Location: ../index.php?error=Email não encontrado!");
        exit();
    }
}
?>