<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas professores podem acessar
if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Enviar Mensagens";
include '../includes/head.php';

// Buscar alunos da turma do professor
$query = $conn->prepare("SELECT u.id, u.nome FROM users u
                        JOIN alunos a ON u.id = a.user_id
                        JOIN turmas t ON a.turma_id = t.id
                        WHERE t.professor_id = ?");
$query->bind_param("i", $_SESSION['user_id']);
$query->execute();
$alunos = $query->get_result();
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">Enviar Mensagem para Aluno</h2>

    <form method="POST" action="../controllers/process_mensagens.php" class="card p-4 mx-auto shadow-lg" style="max-width: 500px;">
        <div class="mb-3">
            <label class="form-label">Selecionar Aluno</label>
            <select name="aluno_id" class="form-select" required>
                <option value="">Escolha um aluno</option>
                <?php while ($aluno = $alunos->fetch_assoc()) : ?>
                    <option value="<?= $aluno['id']; ?>"><?= htmlspecialchars($aluno['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Mensagem</label>
            <textarea name="mensagem" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Enviar</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
