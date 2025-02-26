<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Buscar produtos da categoria 'bar'
$query = "SELECT id, nome, preco, stock, imagem FROM produtos WHERE categoria = 'bar' ORDER BY nome ASC";
$result = $conn->query($query);

$pageTitle = "Gestão do Bar";
include '../includes/head.php';
?>

<link rel="stylesheet" href="../public/css/produtos.css">

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
    <div class="text-center">
        <h1 class="fw-bold titulo-sombreado">
            <i class="bi bi-cup-straw"></i> Gestão do Bar
        </h1>
        <p class="text-muted">Faça a gestão dos produtos disponíveis no bar.</p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="add_produto_bar.php" class="btn btn-success px-4 py-2"><i class="bi bi-plus-lg"></i> Adicionar Produto</a>
    </div>

    <div class="row">
        <?php while ($produto = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 position-relative">
                    <!-- Verifica se o produto tem imagem, senão usa um padrão -->
                    <img src="../uploads/produtos/<?= !empty($produto['imagem']) ? $produto['imagem'] : 'no-image.png'; ?>" 
                        class="card-img-top" alt="<?= htmlspecialchars($produto['nome']); ?>" 
                        style="height: 200px; object-fit: cover;">
                    
                    <!-- Se o produto estiver esgotado, exibe o aviso -->
                    <?php if ($produto['stock'] == 0): ?>
                        <div class="esgotado-overlay">Esgotado</div>
                    <?php endif; ?>

                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($produto['nome']); ?></h5>
                        <p class="fw-bold text-success">€<?= number_format($produto['preco'], 2, ',', '.'); ?></p>
                        <p>Estoque: <span class="badge bg-<?= ($produto['stock'] == 0) ? 'danger' : 'primary'; ?>">
                            <?= htmlspecialchars($produto['stock']); ?>
                        </span> unidades</p>
                        <a href="edit_produto_bar.php?id=<?= $produto['id']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="delete_produto.php?id=<?= $produto['id']; ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                            <i class="bi bi-trash"></i> Excluir
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>