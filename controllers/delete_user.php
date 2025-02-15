<?php
include '../config/db.php';
$adminOnly = true;
include 'auth_check.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $conn->prepare("DELETE FROM users WHERE id = ?");
    $query->bind_param("i", $id);
    if ($query->execute()) {
        header("Location: ../views/manage_users.php");
        exit();
    } else {
        echo "Erro ao excluir usuário.";
    }
}
?>
