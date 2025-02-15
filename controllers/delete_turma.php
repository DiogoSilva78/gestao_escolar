<?php
include '../config/db.php';
$adminOnly = true;
include 'auth_check.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verifica se há alunos associados à turma antes de excluir
    $check_query = $conn->prepare("SELECT COUNT(*) AS total FROM alunos WHERE turma_id = ?");
    $check_query->bind_param("i", $id);
    $check_query->execute();
    $check_result = $check_query->get_result();
    $row = $check_result->fetch_assoc();

    if ($row['total'] > 0) {
        echo "Erro: Não é possível excluir a turma, pois há alunos associados.";
        exit();
    }

    // Se não houver alunos associados, exclui a turma
    $query = $conn->prepare("DELETE FROM turmas WHERE id = ?");
    $query->bind_param("i", $id);
    
    if ($query->execute()) {
        header("Location: ../views/manage_turmas.php");
        exit();
    } else {
        echo "Erro ao excluir turma.";
    }
}
?>
