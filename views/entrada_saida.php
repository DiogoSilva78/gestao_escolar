<?php
include '../config/db.php';
include '../controllers/auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'];

    // Procurar o aluno pelo RFID
    $query = $conn->prepare("SELECT id, nome FROM users WHERE rfid_tag = ? AND tipo = 'aluno'");
    $query->bind_param("s", $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $aluno = $result->fetch_assoc();
        $aluno_id = $aluno['id'];

        // Verificar se a última entrada foi uma saída ou entrada
        $query_last = $conn->prepare("SELECT tipo FROM entradas_saidas WHERE user_id = ? ORDER BY data DESC LIMIT 1");
        $query_last->bind_param("i", $aluno_id);
        $query_last->execute();
        $result_last = $query_last->get_result();
        $ultima_movimentacao = $result_last->fetch_assoc();

        // Definir se é uma entrada ou saída
        $tipo = ($ultima_movimentacao && $ultima_movimentacao['tipo'] == 'entrada') ? 'saida' : 'entrada';

        // Registar na base de dados
        $query_insert = $conn->prepare("INSERT INTO entradas_saidas (user_id, tipo) VALUES (?, ?)");
        $query_insert->bind_param("is", $aluno_id, $tipo);
        $query_insert->execute();

        echo json_encode(["status" => "success", "message" => "Registro de $tipo feito com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aluno não encontrado!"]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<?php
    $pageTitle = "Painel do Administrador"; // Define o título da página dinamicamente
    include '../includes/head.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Registrar Entrada/Saída</h2>
<input type="text" id="rfidInput" placeholder="Passe a pulseira" readonly>
<button onclick="registrarMovimentacao()">Registrar</button>

<script>
function registrarMovimentacao() {
    let rfid = $("#rfidInput").val();

    $.post("entrada_saida.php", { rfid: rfid }, function(response) {
        let data = JSON.parse(response);
        alert(data.message);
    });
}

// Simulação do WebSocket para ler RFID
document.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        $("#rfidInput").val("123456789"); // Simula leitura RFID
    }
});
</script>

<?php include '../includes/footer.php'; ?>


</body>
</html>
