<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas administradores podem aceder
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Buscar turmas e professores
$query = "SELECT t.id, t.nome, p.nome AS professor 
          FROM turmas t 
          LEFT JOIN users p ON t.professor_id = p.id 
          ORDER BY t.nome ASC";
$result = $conn->query($query);

$pageTitle = "Gestão de Turmas";
include '../includes/head.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
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

<!-- Painel de Gestão de Turmas -->
<div class="container mt-5">
    <div class="text-center">
        <h1 class="fw-bold titulo-sombreado">
            <i class="bi bi-mortarboard"></i> Gestão de Turmas
        </h1>
        <p class="text-muted">Gerencie turmas e seus professores responsáveis.</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <a href="add_turma.php" class="btn btn-success px-4 py-2">
            <i class="bi bi-plus-lg"></i> Adicionar Turma
        </a>
        <input type="text" id="searchTurma" class="form-control w-25 shadow-sm" placeholder="🔍 Pesquisar turma">
    </div>

    <div class="card shadow-lg border-0 p-3">
        <div class="table-responsive">
            <table class="table table-striped table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nome da Turma</th>
                        <th>Professor Responsável</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($turma = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($turma['nome']); ?></td>
                            <td>
                                <?php if (!empty($turma['professor'])) { ?>
                                    <span class="badge bg-primary"><?= htmlspecialchars($turma['professor']); ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Não Definido</span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="edit_turma.php?id=<?= $turma['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete_turma.php?id=<?= $turma['id']; ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir esta turma?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    document.getElementById("searchTurma").addEventListener("keyup", function () {
        let searchValue = this.value.toLowerCase();
        document.querySelectorAll("tbody tr").forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(searchValue) ? "" : "none";
        });
    });
</script>

</body>
</html>
