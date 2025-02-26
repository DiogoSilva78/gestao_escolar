<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um professor
if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: professor_turmas.php?error=ID da turma inválido.");
    exit();
}

$turma_id = $_GET['id'];

// Buscar informações da turma
$query_turma = $conn->prepare("SELECT nome FROM turmas WHERE id = ?");
$query_turma->bind_param("i", $turma_id);
$query_turma->execute();
$result_turma = $query_turma->get_result();

if ($result_turma->num_rows == 0) {
    header("Location: professor_turmas.php?error=Turma não encontrada.");
    exit();
}

$turma = $result_turma->fetch_assoc();

// Buscar alunos da turma
$query_alunos = $conn->prepare("
    SELECT u.id, u.nome, u.email
    FROM users u
    JOIN alunos a ON u.id = a.user_id
    WHERE a.turma_id = ?
");
$query_alunos->bind_param("i", $turma_id);
$query_alunos->execute();
$result_alunos = $query_alunos->get_result();

$pageTitle = "Alunos da Turma";
include '../includes/head.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">📚 Alunos da Turma: <?= htmlspecialchars($turma['nome']); ?></h2>

    <?php if ($result_alunos->num_rows > 0) : ?>
        <table class="table table-hover shadow-lg mt-4">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($aluno = $result_alunos->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['nome']); ?></td>
                        <td><?= htmlspecialchars($aluno['email']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="text-center mt-4">⚠️ Nenhum aluno registrado nesta turma.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
