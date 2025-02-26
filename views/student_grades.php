<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um aluno
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Minhas Notas";
include '../includes/head.php';

// Buscar as notas do aluno autenticado
$aluno_id = $_SESSION['user_id'];

$query = $conn->prepare("
    SELECT d.nome AS disciplina, m.nome AS modulo, n.nota 
    FROM notas n
    JOIN disciplinas d ON n.disciplina_id = d.id
    JOIN modulos m ON n.modulo_id = m.id
    WHERE n.aluno_id = ?
    ORDER BY d.nome, m.nome
");
$query->bind_param("i", $aluno_id);
$query->execute();
$result = $query->get_result();
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
    <h2 class="text-center text-primary">📖 Minhas Notas</h2>

    <div class="table-responsive mt-4">
        <table class="table table-hover table-striped shadow-lg">
            <thead class="table-dark text-center">
                <tr>
                    <th>Disciplina</th>
                    <th>Módulo</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($nota = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($nota['disciplina']); ?></td>
                            <td><?= htmlspecialchars($nota['modulo']); ?></td>
                            <td><strong><?= number_format($nota['nota'], 1, ',', '.'); ?></strong></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Nenhuma nota disponível.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>