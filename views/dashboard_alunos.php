<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um aluno
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Buscar saldo e localidade reais do aluno no banco de dados
$query_aluno = $conn->prepare("SELECT saldo, localidade FROM users WHERE id = ?");
$query_aluno->bind_param("i", $user_id);
$query_aluno->execute();
$result_aluno = $query_aluno->get_result()->fetch_assoc();

$saldo = $result_aluno['saldo'] ?? 0.00;
$localidade = $result_aluno['localidade'] ?? "Não definido";

// Buscar email caso não esteja na sessão
if (!isset($_SESSION['email'])) {
    $query_user = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $query_user->bind_param("i", $user_id);
    $query_user->execute();
    $result_user = $query_user->get_result();
    $user_data = $result_user->fetch_assoc();
    $_SESSION['email'] = $user_data['email'] ?? "Não definido";
}

// Buscar notificações não lidas
$notificacao_query = $conn->prepare("SELECT COUNT(*) AS nao_lidas FROM mensagens WHERE aluno_id = ? AND lida = FALSE");
$notificacao_query->bind_param("i", $user_id);
$notificacao_query->execute();
$notificacao_result = $notificacao_query->get_result()->fetch_assoc();
$nao_lidas = $notificacao_result['nao_lidas'];

$pageTitle = "Painel do Aluno";
$email = $_SESSION['email'] ?? "Não definido";
$fotoPerfil = !empty($_SESSION['foto']) ? "../uploads/fotos/" . $_SESSION['foto'] : "../assets/img/avatar.png";
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno - School Bracelet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../public/img/logo-epbjc.png" alt="Logo" width="35" height="35" class="me-2">
                <span>School Bracelet</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="student_profile.php">Meu Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="student_class.php">Minha Turma</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Painel do Aluno -->
    <div class="container mt-5">
        <div class="text-center text-primary">
            <h1 class="fw-bold">📚 Painel do Aluno</h1>
        </div>

        <div class="student-panel mt-4 p-4">
            <!-- Card com Foto e Dados Pessoais -->
            <div class="card shadow-lg p-3 mb-4 text-center">
                <img src="<?= $fotoPerfil; ?>" class="profile-img mx-auto" alt="Foto do Aluno">
                <h4 class="mt-2"><?= htmlspecialchars($_SESSION['nome']); ?></h4>
                <p><i class="bi bi-envelope"></i> <?= htmlspecialchars($email); ?></p>
                <p><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($localidade); ?></p>
            </div>

            <!-- Linha 1: Saldo e Extrato -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 bg-primary text-white student-card">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-wallet2" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2">Saldo Disponível</h6>
                            <p class="fw-bold" style="font-size: 1.5rem;">€<?= number_format($saldo, 2, ',', '.'); ?>
                            </p>
                            <?php if ($saldo < 2): ?>
                                <p class="alert alert-warning p-1 mb-0" style="font-size: 0.85rem;">⚠️ Saldo baixo! Carregue
                                    mais saldo.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 student-card">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-receipt text-success" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2">Extrato</h6>
                            <a href="extrato.php" class="btn btn-success btn-sm w-100">Ver Movimentos</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linha 2: Horários, Notas, Avisos e Mensagens -->
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 student-card">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-calendar-week text-warning" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2">Meu Horário</h6>
                            <a href="student_schedule.php" class="btn btn-outline-warning btn-sm w-100">Aceder</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 student-card">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-book text-primary" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2">Minhas Notas</h6>
                            <a href="student_grades.php" class="btn btn-outline-primary btn-sm w-100">Aceder</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 student-card">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-megaphone text-danger" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2">Avisos</h6>
                            <a href="student_announcements.php" class="btn btn-outline-danger btn-sm w-100">Aceder</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 student-card">
                        <div class="card-body text-center p-3">
                            <i class="bi bi-envelope text-danger" style="font-size: 2rem;"></i>
                            <h6 class="card-title mt-2">Mensagens</h6>
                            <a href="student_mensagens.php" class="btn btn-outline-danger btn-sm w-100">
                                Aceder
                                <?php if ($nao_lidas > 0)
                                    echo "<span class='badge bg-danger'>$nao_lidas</span>"; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rodapé -->
    <?php include '../includes/footer.php'; ?>
</body>

</html>