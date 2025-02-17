<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Procurar histórico de entradas e saídas
$query = "SELECT users.nome, entradas_saidas.tipo, entradas_saidas.data 
          FROM entradas_saidas 
          JOIN users ON entradas_saidas.user_id = users.id 
          ORDER BY entradas_saidas.data DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Entradas e Saídas</title>
</head>
<body>

<h2>Histórico de Movimentações</h2>
<a href="dashboard_admin.php">Voltar</a>

<table border="1">
    <tr>
        <th>Aluno</th>
        <th>Tipo</th>
        <th>Data</th>
    </tr>
    <?php while ($mov = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($mov['nome']); ?></td>
            <td><?php echo ucfirst($mov['tipo']); ?></td>
            <td><?php echo date("d/m/Y H:i", strtotime($mov['data'])); ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
