<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

// Buscar professores disponíveis (apenas usuários do tipo "professor")
$professores_query = $conn->query("SELECT id, nome FROM users WHERE tipo = 'professor'");

// Processar formulário de criação de turma
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_turma = $_POST['nome_turma'];
    $ano = $_POST['ano'];
    $area_curso = $_POST['area_curso'];
    $abreviatura = $_POST['abreviatura'];
    $letra_turma = $_POST['letra_turma'];
    $professor_id = !empty($_POST['professor_id']) ? $_POST['professor_id'] : NULL;

    // Inserir turma no banco de dados
    $query = $conn->prepare("INSERT INTO turmas (nome, ano, area_curso, abreviatura, letra_turma, professor_id) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("sisssi", $nome_turma, $ano, $area_curso, $abreviatura, $letra_turma, $professor_id);

    if ($query->execute()) {
        header("Location: manage_turmas.php?success=Turma criada com sucesso!");
        exit();
    } else {
        $error = "Erro ao criar turma.";
    }
}
?>

<?php 
$pageTitle = "Criar Turma"; 
include '../includes/head.php'; 
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">Adicionar Nova Turma</h2>

    <form method="POST" class="card p-4 mx-auto shadow-lg" style="max-width: 500px;">
        <div class="mb-3">
            <label class="form-label">Nome da Turma</label>
            <input type="text" name="nome_turma" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ano</label>
            <select name="ano" class="form-select" required>
                <option value="">Selecione o Ano</option>
                <option value="10">10º Ano</option>
                <option value="11">11º Ano</option>
                <option value="12">12º Ano</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Área do Curso</label>
            <select name="area_curso" class="form-select" required>
                <option value="">Selecione a Área</option>
                <option value="Informática">Informática</option>
                <option value="Eletrônica">Eletrônica</option>
                <option value="Mecânica">Mecânica</option>
                <option value="Gestão">Gestão</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Abreviatura do Curso</label>
            <input type="text" name="abreviatura" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Letra da Turma</label>
            <input type="text" name="letra_turma" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Professor Responsável</label>
            <select name="professor_id" class="form-select">
                <option value="">Sem Professor</option>
                <?php while ($professor = $professores_query->fetch_assoc()) : ?>
                    <option value="<?= $professor['id']; ?>"><?= htmlspecialchars($professor['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-100">Criar Turma</button>
    </form>
</div>


<?php include '../includes/footer.php'; ?>
