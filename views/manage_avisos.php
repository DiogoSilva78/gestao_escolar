<?php
include '../config/db.php';
$adminOnly = true; // Garante que apenas administradores acessem
include '../controllers/auth_check.php';

$pageTitle = "Gestão de Avisos";
include '../includes/head.php';

// Adicionar novo aviso
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_aviso'])) {
    $titulo = $_POST['titulo'];
    $mensagem = $_POST['mensagem'];

    $query = $conn->prepare("INSERT INTO avisos (titulo, mensagem) VALUES (?, ?)");
    $query->bind_param("ss", $titulo, $mensagem);
    $query->execute();
}

// Excluir aviso
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = $conn->prepare("DELETE FROM avisos WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    header("Location: manage_avisos.php");
    exit();
}

// Buscar todos os avisos
$query = "SELECT * FROM avisos ORDER BY data_publicacao DESC";
$result = $conn->query($query);
?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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
    <h2 class="text-center text-primary">📢 Gestão de Avisos</h2>

    <!-- Formulário de Novo Aviso -->
    <div class="card p-4 shadow-lg mt-4">
        <h5 class="text-primary">Adicionar Novo Aviso</h5>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mensagem</label>
                <textarea name="mensagem" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" name="add_aviso" class="btn btn-success w-100">Publicar Aviso</button>
        </form>
    </div>

    <!-- Lista de Avisos -->
    <div class="table-responsive mt-4">
        <table class="table table-hover shadow-lg">
            <thead class="table-dark text-center">
                <tr>
                    <th>Título</th>
                    <th>Mensagem</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php while ($aviso = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($aviso['titulo']); ?></td>
                        <td><?= htmlspecialchars($aviso['mensagem']); ?></td>
                        <td><?= date("d/m/Y", strtotime($aviso['data_publicacao'])); ?></td>
                        <td>
                            <a href="manage_avisos.php?delete=<?= $aviso['id']; ?>" class="btn btn-sm btn-danger">🗑
                                Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>


<?php include '../includes/footer.php'; ?>