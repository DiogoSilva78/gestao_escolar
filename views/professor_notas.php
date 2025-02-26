<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário é um professor
if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

// Verifica se a turma foi especificada
if (!isset($_GET['turma_id']) || empty($_GET['turma_id'])) {
    header("Location: professor_turmas.php?error=Turma não especificada.");
    exit();
}

$turma_id = $_GET['turma_id'];

$query = $conn->prepare("
    SELECT u.nome AS aluno, n.nota, m.nome AS modulo, d.nome AS disciplina, n.data_registro
    FROM notas n
    JOIN users u ON n.aluno_id = u.id
    JOIN modulos m ON n.modulo_id = m.id
    JOIN disciplinas d ON n.disciplina_id = d.id
    JOIN alunos a ON a.user_id = u.id
    JOIN turmas t ON a.turma_id = t.id
    WHERE t.id = ?
");
$query->bind_param("i", $turma_id);
$query->execute();
$result = $query->get_result();

$pageTitle = "Notas da Turma";
include '../includes/head.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">📑 Notas dos Alunos</h2>

    <table class="table table-hover mt-4 shadow-lg">
        <thead class="table-dark text-center">
            <tr>
                <th>Aluno</th>
                <th>Disciplina</th>
                <th>Módulo</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['aluno']); ?></td>
                    <td><?= htmlspecialchars($row['disciplina']); ?></td>
                    <td><?= htmlspecialchars($row['modulo']); ?></td>
                    <td><?= htmlspecialchars($row['nota']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Botão para Exportar PDF -->
    <div class="text-center mt-4">
        <a href="../controllers/export_notas.php?turma_id=<?= $turma_id; ?>" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Gerar PDF
        </a>
    </div>

    <!-- Botão para voltar às turmas -->
    <div class="text-center mt-3">
        <a href="professor_turmas.php" class="btn btn-secondary">Voltar às Turmas</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
