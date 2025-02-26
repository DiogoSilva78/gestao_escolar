<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um professor
if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

$professor_id = $_SESSION['user_id'];

// Consulta para buscar as turmas associadas ao professor
$query = $conn->prepare("
    SELECT t.id, t.nome, t.ano, t.area_curso, t.abreviatura, t.letra_turma
    FROM turmas t
    WHERE t.professor_id = ?
");
$query->bind_param("i", $professor_id);
$query->execute();
$result = $query->get_result();

$pageTitle = "Minhas Turmas";
include '../includes/head.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">🎓 Minhas Turmas</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="row mt-4">
            <?php while ($turma = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($turma['nome']); ?></h5>
                            <p><strong>Ano:</strong> <?= htmlspecialchars($turma['ano']); ?></p>
                            <p><strong>Área do Curso:</strong> <?= htmlspecialchars($turma['area_curso']); ?></p>
                            <p><strong>Turma:</strong> <?= htmlspecialchars($turma['abreviatura'] . " " . $turma['letra_turma']); ?>
                            </p>
                            <a href="turma_alunos.php?id=<?= $turma['id']; ?>" class="btn btn-primary">Ver Alunos</a>
                            <a href="professor_notas.php?turma_id=<?= $turma['id']; ?>" class="btn btn-outline-primary">
                                <i class="bi bi-clipboard"></i> Ver Notas
                            </a>
                            <td>
                                <a href="edit_turma_professor.php?id=<?= $turma['id']; ?>" class="btn btn-warning btn-sm">
                                    ✏️ Editar
                                </a>
                            </td>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center mt-4">⚠️ Você ainda não tem turmas atribuídas.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>