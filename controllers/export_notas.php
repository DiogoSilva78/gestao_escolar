<?php
require '../config/db.php';
require '../vendor/autoload.php'; // Certifique-se de que o Dompdf está instalado via Composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Verifica se o professor está logado
include '../controllers/auth_check.php';
if ($_SESSION['user_type'] !== 'professor') {
    die("Acesso negado.");
}

// Verifica se a turma foi enviada via GET
if (!isset($_GET['turma_id']) || empty($_GET['turma_id'])) {
    die("Turma não especificada.");
}

$turma_id = $_GET['turma_id'];

// Consulta as notas dos alunos da turma
$query = $conn->prepare("
    SELECT u.nome AS aluno, d.nome AS disciplina, n.nota, n.modulo
    FROM notas n
    JOIN users u ON n.aluno_id = u.id
    JOIN disciplinas d ON n.disciplina_id = d.id
    WHERE n.turma_id = ?
    ORDER BY u.nome, d.nome
");
$query->bind_param("i", $turma_id);
$query->execute();
$result = $query->get_result();

// Criar HTML para o PDF
$html = "<h2 style='text-align: center;'>Relatório de Notas</h2>";
$html .= "<h4>Turma ID: {$turma_id}</h4>";
$html .= "<table border='1' width='100%' style='border-collapse: collapse; text-align: center;'>
            <thead>
                <tr style='background-color: #ddd;'>
                    <th>Aluno</th>
                    <th>Disciplina</th>
                    <th>Módulo</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>";

while ($row = $result->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row['aluno']}</td>
                <td>{$row['disciplina']}</td>
                <td>{$row['modulo']}</td>
                <td>{$row['nota']}</td>
              </tr>";
}

$html .= "</tbody></table>";

// Configurar Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar PDF para download
$dompdf->stream("Relatorio_Notas_Turma_{$turma_id}.pdf", ["Attachment" => true]);
?>
