<?php
require __DIR__ . '/../vendor/autoload.php'; // Caminho corrigido do autoload

include '../config/db.php';
include '../controllers/auth_check.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuração para evitar erros de memória
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

$user_id = $_SESSION['user_id'];
$query = "SELECT tipo, valor, data FROM transacoes WHERE user_id = ? ORDER BY data DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$html = '<h2>Extrato de Movimentos</h2>
<table border="1" width="100%" cellpadding="5">
<tr>
    <th>Tipo</th>
    <th>Valor (€)</th>
    <th>Data</th>
</tr>';

while ($transacao = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>".ucfirst($transacao['tipo'])."</td>
        <td>".number_format($transacao['valor'], 2, ',', '.')."</td>
        <td>".date("d/m/Y H:i", strtotime($transacao['data']))."</td>
    </tr>";
}

$html .= "</table>";

// Gerar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar o PDF para o navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="extrato.pdf"');
echo $dompdf->output();
?>
