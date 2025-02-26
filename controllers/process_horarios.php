<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $turma_id = $_POST['turma_id'];
    $dia_semana = $_POST['dia_semana'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $disciplina = $_POST['disciplina'];
    $professor = $_POST['professor'];

    $query = $conn->prepare("INSERT INTO horarios (turma_id, dia_semana, hora_inicio, hora_fim, disciplina, professor) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("isssss", $turma_id, $dia_semana, $hora_inicio, $hora_fim, $disciplina, $professor);

    if ($query->execute()) {
        header("Location: ../views/manage_horarios.php?success=Horário adicionado com sucesso!");
    } else {
        header("Location: ../views/manage_horarios.php?error=Erro ao adicionar horário.");
    }
    exit();
}
?>
