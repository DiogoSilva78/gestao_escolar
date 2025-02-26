<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas funcionários podem aceder
if ($_SESSION['user_type'] !== 'funcionario') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<?php 
$pageTitle = "Área do Funcionário"; 
include '../includes/head.php'; 
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard_funcionario.php">
                <img src="../public/img/logo-epbjc.png" alt="Logo" width="35" height="35" class="me-2">
                <span>School Bracelet</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard_funcionario.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="text-center">Área do Funcionário</h2>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-wallet2 text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title">Carregar Saldo</h5>
                    <a href="carregar_saldo.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cart-check text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title">Vender Produtos</h5>
                    <a href="comprar_papelaria.php" class="btn btn-success">Papelaria</a>
                    <a href="comprar_bar.php" class="btn btn-danger">Bar</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-door-open text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title">Portaria</h5>
                    <a href="portaria.php" class="btn btn-primary">Acessar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <a href="../controllers/logout.php" class="btn btn-outline-danger">Sair</a>
</div>

<?php include '../includes/footer.php'; ?>


</body>
</html>
