<?php
require __DIR__ . '/../vendor/autoload.php';


include '../config/db.php';
include '../controllers/auth_check.php';

use Dompdf\Dompdf;

$user_id = $_SESSION['user_id'];
$query = "SELECT tipo, valor, data FROM transacoes WHERE user_id = ? ORDER BY data DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$html = "<h2>Extrato de Movimentos</h2>
<table border='1' width='100%' cellpadding='5'>
<tr>
    <th>Tipo</th>
    <th>Valor (€)</th>
    <th>Data</th>
</tr>";

while ($transacao = $result->fetch_assoc()) {
    $html .= "<tr>
        <td>".ucfirst($transacao['tipo'])."</td>
        <td>".number_format($transacao['valor'], 2, ',', '.')."</td>
        <td>".date("d/m/Y H:i", strtotime($transacao['data']))."</td>
    </tr>";
}

$html .= "</table>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper("A4", "portrait");
$dompdf->render();
$dompdf->stream("extrato_aluno.pdf");
?>
