<?php
include '../config/db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rfid'])) {
    $rfid = $_POST['rfid'];

    // Buscar aluno pelo RFID
    $query = $conn->prepare("SELECT id, nome FROM users WHERE rfid_tag = ? AND tipo = 'aluno'");
    $query->bind_param("s", $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $aluno = $result->fetch_assoc();
        $aluno_id = $aluno['id'];
        $aluno_nome = $aluno['nome'];

        // Verifica o último registro
        $query_ultimo = $conn->prepare("SELECT tipo FROM portaria WHERE user_id = ? ORDER BY data_hora DESC LIMIT 1");
        $query_ultimo->bind_param("i", $aluno_id);
        $query_ultimo->execute();
        $result_ultimo = $query_ultimo->get_result();

        if ($result_ultimo->num_rows > 0) {
            $ultimo_registro = $result_ultimo->fetch_assoc();
            $novo_tipo = ($ultimo_registro['tipo'] == 'entrada') ? 'saida' : 'entrada';
        } else {
            $novo_tipo = 'entrada';
        }

        // Registrar a entrada/saída no banco de dados
        $insert = $conn->prepare("INSERT INTO portaria (user_id, tipo) VALUES (?, ?)");
        $insert->bind_param("is", $aluno_id, $novo_tipo);
        $insert->execute();

        echo json_encode(["status" => "success", "message" => "Registro de $novo_tipo efetuado para $aluno_nome"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aluno não encontrado!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Nenhum RFID recebido"]);
}
?>
