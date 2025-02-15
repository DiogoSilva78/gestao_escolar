<?php
include '../config/db.php';
include '../controllers/auth_check.php';

$user_id = $_SESSION['user_id'];

// Buscar dados do aluno
$query = $conn->prepare("SELECT nome, saldo FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Aluno</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <h2>Bem-vindo, <?php echo htmlspecialchars($user['nome']); ?>!</h2>
    <p>Saldo disponível: <strong>€<?php echo number_format($user['saldo'], 2, ',', '.'); ?></strong></p>
    
    <a href="extrato.php">Ver Extrato</a> |  
    <a href="horarios.php">Ver Horário</a> |  
    <a href="../controllers/logout.php">Logout</a>
</body>
</html>
