<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Se for um aluno e tentar acessar a área de admin, redireciona
if (isset($adminOnly) && $_SESSION['user_type'] !== 'admin') {
    header("Location: dashboard_aluno.php");
    exit();
}
?>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Se for um aluno e tentar acessar a área de admin, redireciona
if (isset($adminOnly) && $_SESSION['user_type'] !== 'admin') {
    header("Location: dashboard_aluno.php");
    exit();
}
?>
