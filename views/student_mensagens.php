<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas alunos podem acessar
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Minhas Mensagens";
include '../includes/head.php';

// Buscar mensagens para o aluno logado
$query = $conn->prepare("SELECT m.*, u.nome AS professor_nome FROM mensagens m
                         JOIN users u ON m.professor_id = u.id
                         WHERE m.aluno_id = ?
                         ORDER BY m.data_envio DESC");
$query->bind_param("i", $_SESSION['user_id']);
$query->execute();
$mensagens = $query->get_result();
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard_alunos.php">
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
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container mt-5">
    <h2 class="text-center text-primary">📩 Minhas Mensagens</h2>

    <?php if ($mensagens->num_rows > 0): ?>
        <div class="list-group mt-4">
            <?php while ($msg = $mensagens->fetch_assoc()) : ?>
                <div class="list-group-item">
                    <h5><i class="bi bi-person-fill"></i> Professor <?= htmlspecialchars($msg['professor_nome']); ?></h5>
                    <p><?= nl2br(htmlspecialchars($msg['mensagem'])); ?></p>
                    <small class="text-muted"><?= date("d/m/Y H:i", strtotime($msg['data_envio'])); ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center mt-4">Nenhuma mensagem recebida.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
