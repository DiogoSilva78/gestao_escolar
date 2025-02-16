<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas funcionários podem acessar
if ($_SESSION['user_type'] !== 'funcionario') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'];
    $valor = $_POST['valor'];

    // Verificar se o RFID pertence a um aluno
    $query = $conn->prepare("SELECT id, nome, saldo FROM users WHERE rfid_tag = ? AND tipo = 'aluno'");
    $query->bind_param("s", $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $aluno = $result->fetch_assoc();
        $novo_saldo = $aluno['saldo'] + $valor;

        // Atualizar saldo
        $update = $conn->prepare("UPDATE users SET saldo = ? WHERE id = ?");
        $update->bind_param("di", $novo_saldo, $aluno['id']);
        $update->execute();

        echo json_encode(["status" => "success", "message" => "Saldo carregado com sucesso!", "saldo" => $novo_saldo]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aluno não encontrado!"]);
    }

    $log_transacao = $conn->prepare("INSERT INTO transacoes (user_id, tipo, valor) VALUES (?, 'carregamento', ?)");
    $log_transacao->bind_param("id", $aluno['id'], $valor);
    $log_transacao->execute();

    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carregar Saldo</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <h2>Carregar Saldo</h2>
    <input type="text" id="rfidInput" placeholder="Passe a pulseira" readonly>
    <input type="number" id="valor" placeholder="Valor (€)">
    <button onclick="carregarSaldo()">Carregar</button>

    <script>
        function carregarSaldo() {
            let rfid = $("#rfidInput").val();
            let valor = $("#valor").val();

            $.post("carregar_saldo.php", { rfid: rfid, valor: valor }, function (response) {
                let data = JSON.parse(response);
                alert(data.message);
            });
        }

        // Simulação do WebSocket para ler RFID (trocar pelo real)
        document.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                $("#rfidInput").val("123456789"); // Simula leitura RFID
            }
        });

        $(document).ready(function () {
            let socket = new WebSocket("ws://localhost:8080"); // Conectar ao WebSocket

            socket.onmessage = function (event) {
                let rfid = event.data.trim(); // Captura o RFID lido
                $("#rfidInput").val(rfid); // Preenche automaticamente o campo
            };

            socket.onerror = function (event) {
                console.error("Erro no WebSocket: ", event);
            };
        }); 
    </script>

</body>

</html>