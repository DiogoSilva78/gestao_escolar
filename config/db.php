<?php
$host = "localhost"; // Servidor do banco de dados
$user = "root"; // Usuário do MySQL (padrão no XAMPP)
$pass = ""; // Senha do MySQL (deixa vazio se for padrão)
$dbname = "gestao_escolar"; // Nome atualizado da base de dados

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Definir charset para evitar problemas com acentos
$conn->set_charset("utf8");
?>
