<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um aluno
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Avisos da Escola";
include '../includes/head.php';

// Buscar os avisos da escola
$query_avisos = $conn->query("SELECT * FROM avisos ORDER BY data_publicacao DESC");
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="../public/css/dashboards.css">
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="../views/dashboard_alunos.php">
            <img src="../public/img/logo-epbjc.png" alt="Logo" width="35" height="35" class="me-2">
            <span>School Bracelet</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="student_profile.php">Meu Perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="student_class.php">Minha Turma</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                        href="../controllers/logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2 class="text-center text-danger">📢 Avisos da Escola</h2>

    <div class="list-group shadow-sm">
        <?php if ($query_avisos->num_rows > 0): ?>
            <?php while ($aviso = $query_avisos->fetch_assoc()): ?>
                <div class="list-group-item">
                    <h5 class="mb-1"><?= htmlspecialchars($aviso['titulo']); ?></h5>
                    <p class="mb-1"><?= nl2br(htmlspecialchars($aviso['mensagem'])); ?></p>
                    <small class="text-muted">Publicado em
                        <?= date("d/m/Y H:i", strtotime($aviso['data_publicacao'])); ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center text-muted">Nenhum aviso disponível no momento.</div>
        <?php endif; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>;