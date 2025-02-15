<?php
include '../config/db.php';
$adminOnly = true; // Garante que apenas admins podem acessar
include '../controllers/auth_check.php';
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Administrador</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <h2>Bem-vindo, Administrador!</h2>

    <ul>
        <li><a href="manage_users.php">Gerir Usuários</a></li>
        <li><a href="manage_turmas.php">Gerir Turmas</a></li>
        <li><a href="manage_papelaria.php">Gerir Papelaria</a></li>
        <li><a href="manage_bar.php">Gerir Bar</a></li>
        <li><a href="manage_portaria.php">Gerir Portaria</a></li>
    </ul>

    <a href="../controllers/logout.php">Logout</a>
</body>
</html>
