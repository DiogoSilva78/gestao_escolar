<?php
include '../config/db.php';
include '../controllers/auth_check.php';
$pageTitle = "Título da Página"; // Define o nome da página
include '../includes/head.php';

// Buscar usuários
$query = "SELECT * FROM users ORDER BY nome ASC";
$result = $conn->query($query);
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
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Utilizadores</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_turmas.php">Turmas</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="text-primary text-center mb-4">Gestão de Utilizadores</h2>
    

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="add_user.php" class="btn btn-success"><i class="bi bi-person-plus"></i> Adicionar Utilizadores</a>
        <input type="text" id="searchUser" class="form-control w-25" placeholder="🔍 Pesquisar utilizador">
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover shadow-lg">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php while ($user = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nome']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?= ($user['tipo'] == 'admin') ? 'danger' : (($user['tipo'] == 'funcionario') ? 'warning' : 'primary'); ?>">
                                <?= ucfirst($user['tipo']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="../controllers/delete_user.php?id=<?= $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include '../includes/footer.php'; ?>

<script>
document.getElementById("searchUser").addEventListener("keyup", function () {
    let searchValue = this.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(searchValue) ? "" : "none";
    });
});
</script>

</body>
</html>
