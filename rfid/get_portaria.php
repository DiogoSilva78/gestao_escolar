<?php
include '../config/db.php';

// Buscar os últimos 6 registros
$query = "SELECT p.*, u.nome FROM portaria p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.data_hora DESC LIMIT 6";
$result = $conn->query($query);
?>

<?php while ($registro = $result->fetch_assoc()) : ?>
    <tr>
        <td><?= htmlspecialchars($registro['nome']); ?></td>
        <td>
            <span class="badge <?= ($registro['tipo'] == 'entrada') ? 'bg-success' : 'bg-danger'; ?>">
                <?= ucfirst($registro['tipo']); ?>
            </span>
        </td>
        <td><?= date("d/m/Y H:i:s", strtotime($registro['data_hora'])); ?></td>
    </tr>
<?php endwhile; ?>
