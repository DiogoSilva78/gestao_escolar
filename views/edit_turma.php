<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_turmas.php?error=ID da turma inválido.");
    exit();
}

$turma_id = $_GET['id'];

// Buscar dados da turma
$query = $conn->prepare("SELECT * FROM turmas WHERE id = ?");
$query->bind_param("i", $turma_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_turmas.php?error=Turma não encontrada.");
    exit();
}

// Buscar professores disponíveis (apenas usuários do tipo "professor")
$professores_query = $conn->query("SELECT id, nome FROM users WHERE tipo = 'professor'");

$turma = $result->fetch_assoc();

// Buscar anos e áreas disponíveis
$anos_query = $conn->query("SELECT DISTINCT ano FROM cursos ORDER BY ano");
$areas_query = $conn->query("SELECT DISTINCT area_curso FROM cursos ORDER BY area_curso");

// Processar edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_turma = $_POST['nome'];
    $ano = $_POST['ano'];
    $area_curso = $_POST['area_curso'];
    $professor_id = $_POST['professor_id'];  // Pegando o ID do professor corretamente
    $abreviatura_curso = $_POST['abreviatura_curso'];
    $letra_turma = $_POST['letra_turma'];

    $update_query = $conn->prepare("UPDATE turmas SET nome = ?, ano = ?, area_curso = ?, professor_id = ?, abreviatura_curso = ?, letra_turma = ? WHERE id = ?");
    $update_query->bind_param("sisissi", $nome_turma, $ano, $area_curso, $professor_id, $abreviatura_curso, $letra_turma, $turma_id);

    if ($update_query->execute()) {
        header("Location: manage_turmas.php?success=Turma atualizada com sucesso!");
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
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4">
                <h2 class="text-center text-primary mb-4">Editar Turma</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nome da Turma</label>
                        <input type="text" name="nome" class="form-control"
                            value="<?= htmlspecialchars($turma['nome']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ano</label>
                        <select name="ano" class="form-select" required>
                            <option value="">Selecione o Ano</option>
                            <?php while ($ano = $anos_query->fetch_assoc()): ?>
                                <option value="<?= $ano['ano']; ?>" <?= ($ano['ano'] == $turma['ano']) ? 'selected' : ''; ?>>
                                    <?= $ano['ano']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Área do Curso</label>
                        <select name="area_curso" class="form-select" required>
                            <option value="">Selecione a Área do Curso</option>
                            <?php while ($area = $areas_query->fetch_assoc()): ?>
                                <option value="<?= $area['area_curso']; ?>" <?= ($area['area_curso'] == $turma['area_curso']) ? 'selected' : ''; ?>>
                                    <?= $area['area_curso']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Professor Responsável</label>
                        <select name="professor_id" class="form-select" required>
                            <option value="">Selecione um Professor</option>
                            <?php while ($professor = $professores_query->fetch_assoc()): ?>
                                <option value="<?= $professor['id']; ?>" <?= ($turma['professor_id'] == $professor['id']) ? 'selected' : ''; ?>>
                                    <?= $professor['nome']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Abreviatura do Curso</label>
                        <input type="text" name="abreviatura_curso" class="form-control"
                            value="<?= htmlspecialchars($turma['abreviatura_curso']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Letra da Turma</label>
                        <input type="text" name="letra_turma" class="form-control"
                            value="<?= htmlspecialchars($turma['letra_turma']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-save"></i> Salvar Alterações
                    </button>
                    <a href="manage_turmas.php" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>



<?php include '../includes/footer.php'; ?>