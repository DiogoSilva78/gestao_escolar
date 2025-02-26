<?php
session_start();
include '../config/db.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['rfid']) || empty($_POST['rfid'])) {
        echo json_encode(["status" => "error", "message" => "Nenhuma tag RFID recebida!"]);
        exit();
    }

    $rfid = trim($_POST['rfid']);

    // Debug: Exibir a tag RFID recebida
    error_log("RFID recebido: " . $rfid);

    // Buscar o usuário pelo RFID
    $query = $conn->prepare("SELECT id, nome, tipo FROM users WHERE rfid_tag = ?");
    $query->bind_param("s", $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo'];

        // Redirecionar conforme o tipo de usuário
        if ($user['tipo'] == 'admin') {
            echo json_encode(["status" => "success", "redirect" => "dashboard_admin.php"]);
        } elseif ($user['tipo'] == 'funcionario') {
            echo json_encode(["status" => "success", "redirect" => "dashboard_funcionario.php"]);
        } elseif ($user['tipo'] == 'professor') {
            echo json_encode(["status" => "success", "redirect" => "dashboard_professor.php"]);
        } else {
            echo json_encode(["status" => "success", "redirect" => "dashboard_alunos.php"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "RFID não encontrado!"]);
    }
    exit();
}
?>
