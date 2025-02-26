<?php
require '../vendor/autoload.php'; // Carregar Dompdf
include '../config/db.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Criar instância do Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// Filtros
$filtro_data = $_GET['data'] ?? "";
$filtro_tipo = $_GET['tipo'] ?? "";

// Consulta com filtros
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

// Montar HTML do PDF
$html = '
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    h2 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
    th { background-color: #004a99; color: white; }
</style>
<h2>Relatório de Vendas</h2>
<table>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Valor (€)</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>';

while ($transacao = $result->fetch_assoc()) {
    $html .= "<tr>
                <td>" . htmlspecialchars($transacao['nome']) . "</td>
                <td>" . ucfirst($transacao['tipo']) . "</td>
                <td>€" . number_format($transacao['valor'], 2, ',', '.') . "</td>
                <td>" . date("d/m/Y H:i", strtotime($transacao['data'])) . "</td>
              </tr>";
}

$html .= '</tbody></table>';

// Gerar o PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("relatorio_vendas.pdf", ["Attachment" => false]);
?>
