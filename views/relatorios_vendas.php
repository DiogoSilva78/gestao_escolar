<?php
include '../config/db.php';
include '../controllers/auth_check.php';

$pageTitle = "Relatórios de Vendas";
include '../includes/head.php';

$filtro_data = $_GET['data'] ?? "";
$filtro_tipo = $_GET['tipo'] ?? "";

$query = "SELECT t.*, u.nome FROM transacoes t 
          JOIN users u ON t.user_id = u.id 
          WHERE t.tipo = 'compra'";

if (!empty($filtro_data)) {
    $query .= " AND DATE(t.data) = '$filtro_data'";
}

if (!empty($filtro_tipo)) {
    $query .= " AND t.tipo = '$filtro_tipo'";
}

$query .= " ORDER BY t.data DESC";

$result = $conn->query($query);
?>

<div class="container mt-5">
    <h2 class="text-primary text-center">📑 Relatórios de Vendas</h2>

    <form method="GET" class="row g-3 mt-3">
        <div class="col-md-4">
            <label class="form-label">Filtrar por Data</label>
            <input type="date" name="data" class="form-control" value="<?= $filtro_data; ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Filtrar por Tipo</label>
            <select name="tipo" class="form-select">
                <option value="">Todos</option>
                <option value="bar" <?= $filtro_tipo === 'bar' ? 'selected' : ''; ?>>Bar</option>
                <option value="papelaria" <?= $filtro_tipo === 'papelaria' ? 'selected' : ''; ?>>Papelaria</option>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">🔍 Filtrar</button>
        </div>
    </form>

    <div class="text-end mb-3">
    <a href="exportar_relatorio_pdf.php?data=<?= $filtro_data; ?>&tipo=<?= $filtro_tipo; ?>" 
       class="btn btn-danger">
       <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
    </a>
</div>


    <table class="table table-striped mt-4">
        <thead class="table-dark text-center">
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Valor (€)</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php while ($transacao = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($transacao['nome']); ?></td>
                    <td><?= ucfirst($transacao['tipo']); ?></td>
                    <td>€<?= number_format($transacao['valor'], 2, ',', '.'); ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($transacao['data'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
