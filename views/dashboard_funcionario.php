<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas funcionários podem acessar
if ($_SESSION['user_type'] !== 'funcionario') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Funcionário</title>
</head>
<body>

<h2>Bem-vindo, Funcionário!</h2>

<ul>
    <li><a href="carregar_saldo.php">Carregar Saldo na Papelaria</a></li>
    <li><a href="comprar_papelaria.php">Vender Produtos na Papelaria</a></li>
    <li><a href="comprar_bar.php">Vender Produtos no Bar</a></li>
</ul>

<a href="../controllers/logout.php">Logout</a>

</body>
</html>
