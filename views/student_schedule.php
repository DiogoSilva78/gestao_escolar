<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um aluno
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

// Busca a turma do aluno autenticado
$user_id = $_SESSION['user_id'];
$query_turma = $conn->prepare("SELECT turma_id FROM alunos WHERE user_id = ?");
$query_turma->bind_param("i", $user_id);
$query_turma->execute();
$result_turma = $query_turma->get_result();

if ($result_turma->num_rows == 0) {
    die("Erro: Não foi possível encontrar a turma do aluno.");
}

$turma = $result_turma->fetch_assoc();
$turma_id = $turma['turma_id'];

// Busca os horários da turma do aluno
$query_horarios = $conn->prepare("
    SELECT * FROM horarios 
    WHERE turma_id = ? 
    ORDER BY FIELD(dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'), hora_inicio
");
$query_horarios->bind_param("i", $turma_id);
$query_horarios->execute();
$result_horarios = $query_horarios->get_result();

$pageTitle = "Horário Escolar";
include '../includes/head.php';

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
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="text-center text-primary">📅 Meu Horário Escolar</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-striped shadow">
            <thead class="table-dark text-center">
                <tr>
                    <th>Dia da Semana</th>
                    <th>Hora Início</th>
                    <th>Hora Fim</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if ($result_horarios->num_rows > 0): ?>
                    <?php while ($horario = $result_horarios->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($horario['dia_semana']); ?></td>
                            <td><?= htmlspecialchars($horario['hora_inicio']); ?></td>
                            <td><?= htmlspecialchars($horario['hora_fim']); ?></td>
                            <td><?= htmlspecialchars($horario['disciplina']); ?></td>
                            <td><?= htmlspecialchars($horario['professor']); ?></td>
                        </tr>
                        
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">Nenhum horário cadastrado para sua turma.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<?php include '../includes/footer.php'; ?>
