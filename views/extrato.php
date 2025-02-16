<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// O aluno só pode ver seu próprio extrato
$user_id = $_SESSION['user_id'];

// Filtragem por data
$filtro = "";
if (isset($_GET['data_inicio']) && isset($_GET['data_fim'])) {
    $data_inicio = $_GET['data_inicio'];
    $data_fim = $_GET['data_fim'];
    $filtro = "AND data BETWEEN '$data_inicio' AND '$data_fim'";
}

// Buscar transações do aluno
$query = "SELECT tipo, valor, data FROM transacoes WHERE user_id = ? $filtro ORDER BY data DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato do Aluno</title>
</head>
<body>

<h2>Extrato de Movimentos</h2>
<a href="dashboard_alunos.php">Voltar</a>

<!-- Formulário de filtro por data -->
<form method="GET">
    <label>De: <input type="date" name="data_inicio"></label>
    <label>Até: <input type="date" name="data_fim"></label>
    <input type="submit" value="Filtrar">
</form>

<table border="1">
    <tr>
        <th>Tipo</th>
        <th>Valor (€)</th>
        <th>Data</th>
    </tr>
    <?php while ($transacao = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo ucfirst($transacao['tipo']); ?></td>
            <td><?php echo number_format($transacao['valor'], 2, ',', '.'); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($transacao['data'])); ?></td>
        </tr>
    <?php } ?>
</table>

<a href="extrato_pdf.php">Gerar PDF</a>

</body>
</html>
