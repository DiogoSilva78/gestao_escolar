<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um professor
if ($_SESSION['user_type'] !== 'professor') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Painel do Professor";
include '../includes/head.php';

// Buscar turmas associadas ao professor logado
$professor_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT id, nome, abreviatura, letra_turma FROM turmas WHERE professor_id = ?");
$query->bind_param("i", $professor_id);
$query->execute();
$turmas = $query->get_result();
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard_professor.php">
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
    <h2 class="text-center text-primary">📚 Painel do Professor</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-people text-primary" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-2">Minhas Turmas</h5>
                    <a href="professor_turmas.php" class="btn btn-outline-primary w-100">Aceder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-book text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-2">Gerir Notas</h5>
                    <a href="professor_notas.php" class="btn btn-outline-success w-100">Aceder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-chat-dots text-warning" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-2">Enviar Mensagens</h5>
                    <a href="professor_mensagens.php" class="btn btn-outline-warning w-100">Aceder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-week text-warning" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-2">Horário das Aulas</h5>
                    <a href="professor_horarios.php" class="btn btn-outline-warning w-100">Aceder</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard text-success" style="font-size: 3rem;"></i>
                    <h5 class="card-title mt-2">Visualizar Notas</h5>
                    <a href="professor_notas.php" class="btn btn-outline-success w-100">Aceder</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Desempenho -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">📊 Desempenho das Turmas</h5>
                    <canvas id="graficoDesempenho"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("../controllers/get_professor_stats.php")
            .then(response => response.json())
            .then(data => {
                let ctx = document.getElementById("graficoDesempenho").getContext("2d");
                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: "Média de Notas",
                            data: data.values,
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            borderColor: "rgba(75, 192, 192, 1)",
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            })
            .catch(error => console.error("Erro ao carregar os dados: ", error));
    });
</script>

<?php include '../includes/footer.php'; ?>