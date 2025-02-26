<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

$pageTitle = "Gerir Avisos";
include '../includes/head.php';

// Processar novo aviso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $mensagem = $_POST['mensagem'];

    $query = $conn->prepare("INSERT INTO avisos (titulo, mensagem) VALUES (?, ?)");
    $query->bind_param("ss", $titulo, $mensagem);

    if ($query->execute()) {
        header("Location: admin_avisos.php?success=Aviso publicado com sucesso!");
        exit();
    } else {
        $error = "Erro ao publicar aviso.";
    }
}

// Buscar avisos existentes
$query_avisos = $conn->query("SELECT * FROM avisos ORDER BY data_publicacao DESC");
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">📢 Gerir Avisos</h2>
</div>
