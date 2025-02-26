<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um aluno
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Minha Turma";
include '../includes/head.php';

// Buscar turma do aluno
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("
    SELECT t.nome AS turma, u.nome AS aluno, COALESCE(p.nome, 'Não definido') AS professor
    FROM alunos a
    JOIN turmas t ON a.turma_id = t.id
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN users p ON t.professor_id = p.id
    WHERE a.user_id = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$turmaInfo = $result->fetch_assoc();

// Buscar colegas da turma
$turma_id = $turmaInfo['turma'] ?? null;
$colegas_query = $conn->prepare("
    SELECT u.nome FROM alunos a
    JOIN users u ON a.user_id = u.id
    WHERE a.turma_id = ? AND a.user_id != ?
");
$colegas_query->bind_param("ii", $turma_id, $user_id);
$colegas_query->execute();
$colegas_result = $colegas_query->get_result();
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">🎓 Minha Turma</h2>

    <div class="card shadow-lg p-4">
        <h4>📚 Turma: <?= htmlspecialchars($turmaInfo['turma'] ?? "Sem turma atribuída"); ?></h4>
        <p><strong>Professor Responsável:</strong> <?= htmlspecialchars($turmaInfo['professor'] ?? "Sem professor atribuído"); ?></p>

        <h5 class="mt-4">📌 Meus Colegas:</h5>
        <ul>
            <?php while ($colega = $colegas_result->fetch_assoc()): ?>
                <li><?= htmlspecialchars($colega['nome']); ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
