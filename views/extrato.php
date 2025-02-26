<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// O aluno só pode ver o seu próprio extrato
$user_id = $_SESSION['user_id'];

// Filtragem por data
$filtro = "";
if (isset($_GET['data_inicio']) && isset($_GET['data_fim'])) {
    $data_inicio = $_GET['data_inicio'];
    $data_fim = $_GET['data_fim'];
    $filtro = "AND data BETWEEN '$data_inicio' AND '$data_fim'";
}

// Procurar transações do aluno
$query = "SELECT tipo, valor, data FROM transacoes WHERE user_id = ? $filtro ORDER BY data DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../views/dashboard_alunos.php">
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
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>
<!DOCTYPE html>
<html lang="pt">
<head>
<?php 
$pageTitle = "Extrato do Aluno"; 
include '../includes/head.php'; 
include '../config/db.php';
include '../controllers/auth_check.php';

// Buscar transações do aluno
$user_id = $_SESSION['user_id'];
$query = "SELECT tipo, valor, data FROM transacoes WHERE user_id = ? ORDER BY data DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">Extrato de Movimentos</h2>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Tipo</th>
                    <th>Valor (€)</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transacao = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <span class="badge bg-<?php echo ($transacao['tipo'] == 'compra') ? 'danger' : 'success'; ?>">
                                <?php echo ucfirst($transacao['tipo']); ?>
                            </span>
                        </td>
                        <td>€<?php echo number_format($transacao['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($transacao['data'])); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<?php include '../includes/footer.php'; ?>

</body>
</html>
