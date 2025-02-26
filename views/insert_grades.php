<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas administradores ou professores podem acessar
if ($_SESSION['user_type'] !== 'admin' && $_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Inserir Notas";
include '../includes/head.php';

// Buscar alunos, disciplinas e módulos
$alunos_query = $conn->query("SELECT id, nome FROM users WHERE tipo = 'aluno'");
$disciplinas_query = $conn->query("SELECT id, nome FROM disciplinas");
$modulos_query = $conn->query("SELECT id, nome FROM modulos");

// Inserção de nota
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aluno_id = $_POST['aluno_id'];
    $disciplina_id = $_POST['disciplina_id'];
    $modulo_id = $_POST['modulo_id'];
    $nota = $_POST['nota'];

    // Verifica se a nota já foi inserida para o mesmo aluno, disciplina e módulo
    $check = $conn->prepare("SELECT * FROM notas WHERE aluno_id = ? AND disciplina_id = ? AND modulo_id = ?");
    $check->bind_param("iii", $aluno_id, $disciplina_id, $modulo_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Este aluno já tem uma nota registrada para este módulo.";
    } else {
        // Inserir nova nota
        $query = $conn->prepare("INSERT INTO notas (aluno_id, disciplina_id, modulo_id, nota) VALUES (?, ?, ?, ?)");
        $query->bind_param("iiid", $aluno_id, $disciplina_id, $modulo_id, $nota);
        
        if ($query->execute()) {
            $success = "Nota inserida com sucesso!";
        } else {
            $error = "Erro ao inserir a nota.";
        }
    }
}
?>

<link rel="stylesheet" href="../public/css/dashboard.css">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard_admin.php">
                <img src="../public/img/logo-epbjc.png" alt="Logo" width="35" height="35" class="me-2">
                <span>School Bracelet</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard_admin.php">Painel</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_turmas.php">Turmas</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="text-center text-primary">📝 Inserir Notas</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-lg mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Aluno</label>
            <select name="aluno_id" class="form-select" required>
                <option value="">Selecione um aluno</option>
                <?php while ($aluno = $alunos_query->fetch_assoc()): ?>
                    <option value="<?= $aluno['id']; ?>"><?= htmlspecialchars($aluno['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Disciplina</label>
            <select name="disciplina_id" class="form-select" required>
                <option value="">Selecione uma disciplina</option>
                <?php while ($disciplina = $disciplinas_query->fetch_assoc()): ?>
                    <option value="<?= $disciplina['id']; ?>"><?= htmlspecialchars($disciplina['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Módulo</label>
            <select name="modulo_id" class="form-select" required>
                <option value="">Selecione um módulo</option>
                <?php while ($modulo = $modulos_query->fetch_assoc()): ?>
                    <option value="<?= $modulo['id']; ?>"><?= htmlspecialchars($modulo['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nota</label>
            <input type="number" step="0.1" min="0" max="20" name="nota" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Inserir Nota</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
