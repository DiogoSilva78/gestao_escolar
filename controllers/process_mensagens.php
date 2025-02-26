<?php
include '../config/db.php';
include '../controllers/auth_check.php';

if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professor_id = $_SESSION['user_id'];
    $aluno_id = $_POST['aluno_id'];
    $mensagem = $_POST['mensagem'];

    $query = $conn->prepare("INSERT INTO mensagens (professor_id, aluno_id, mensagem) VALUES (?, ?, ?)");
    $query->bind_param("iis", $professor_id, $aluno_id, $mensagem);

    if ($query->execute()) {
        header("Location: ../views/professor_mensagens.php?success=Mensagem enviada!");
        exit();
    } else {
        header("Location: ../views/professor_mensagens.php?error=Erro ao enviar mensagem.");
        exit();
    }
}
?>
