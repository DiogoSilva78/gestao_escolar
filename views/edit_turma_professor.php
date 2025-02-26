<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas professores podem acessar
if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

// Verifica se uma turma foi selecionada
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: professor_turmas.php?error=Turma não encontrada.");
    exit();
}

$turma_id = $_GET['id'];

// Buscar turma para edição (apenas se for do professor logado)
$query = $conn->prepare("SELECT * FROM turmas WHERE id = ? AND professor_id = ?");
$query->bind_param("ii", $turma_id, $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    header("Location: professor_turmas.php?error=Você não tem permissão para editar esta turma.");
    exit();
}

$turma = $result->fetch_assoc();

// Processar a atualização da turma
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modulos = $_POST['modulos'];
    $observacoes = $_POST['observacoes'];

    $updateQuery = $conn->prepare("UPDATE turmas SET modulos = ?, observacoes = ? WHERE id = ?");
    $updateQuery->bind_param("ssi", $modulos, $observacoes, $turma_id);

    if ($updateQuery->execute()) {
        header("Location: professor_turmas.php?success=Turma atualizada com sucesso!");
        exit();
    } else {
        $error = "Erro ao atualizar turma.";
    }
}
?>

<?php 
$pageTitle = "Editar Turma"; 
include '../includes/head.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">✏️ Editar Turma</h2>

    <form method="POST" class="card p-4 mx-auto shadow-lg" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Nome da Turma</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($turma['nome']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Módulos da Disciplina</label>
            <textarea name="modulos" class="form-control" rows="3" required><?= htmlspecialchars($turma['modulos'] ?? ""); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Observações</label>
            <textarea name="observacoes" class="form-control" rows="3"><?= htmlspecialchars($turma['observacoes'] ?? ""); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
