<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

$pageTitle = "Dashboard - Estatísticas";
include '../includes/head.php';

// Buscar estatísticas gerais
$total_vendas_query = $conn->query("SELECT SUM(valor) AS total FROM transacoes WHERE tipo = 'compra'");
$total_vendas = $total_vendas_query->fetch_assoc()['total'] ?? 0;

$total_carregamentos_query = $conn->query("SELECT SUM(valor) AS total FROM transacoes WHERE tipo = 'carregamento'");
$total_carregamentos = $total_carregamentos_query->fetch_assoc()['total'] ?? 0;

$total_saldo_query = $conn->query("SELECT SUM(saldo) AS total FROM users WHERE tipo = 'aluno'");
$total_saldo = $total_saldo_query->fetch_assoc()['total'] ?? 0;

// Buscar dados de vendas diárias
$vendas_diarias_query = $conn->query("
    SELECT DATE(data) as data, SUM(valor) as total 
    FROM transacoes WHERE tipo = 'compra'
    GROUP BY DATE(data)
    ORDER BY data DESC LIMIT 7
");

$vendas_labels = [];
$vendas_data = [];

while ($row = $vendas_diarias_query->fetch_assoc()) {
    $vendas_labels[] = date("d/m", strtotime($row['data']));
    $vendas_data[] = $row['total'];
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
    <h2 class="text-primary text-center">📊 Estatísticas Gerais</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow text-center p-3">
                <h5 class="text-success">💰 Total de Vendas</h5>
                <h3>€<?= number_format($total_vendas, 2, ',', '.'); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center p-3">
                <h5 class="text-info">💳 Total de Carregamentos</h5>
                <h3>€<?= number_format($total_carregamentos, 2, ',', '.'); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center p-3">
                <h5 class="text-warning">🏦 Saldo Total dos Alunos</h5>
                <h3>€<?= number_format($total_saldo, 2, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-body">
            <h5 class="text-center">📈 Vendas nos Últimos 7 Dias</h5>
            <canvas id="graficoVendas"></canvas>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('graficoVendas').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_reverse($vendas_labels)); ?>,
        datasets: [{
            label: 'Vendas (€)',
            data: <?= json_encode(array_reverse($vendas_data)); ?>,
            borderColor: 'blue',
            backgroundColor: 'rgba(0, 0, 255, 0.2)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php include '../includes/footer.php'; ?>
