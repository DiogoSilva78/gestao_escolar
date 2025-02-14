<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid_tag = $_POST['rfid'];

    // Verifica se o RFID existe no banco de dados
    $query = $conn->prepare("SELECT * FROM users WHERE rfid_tag = ?");
    $query->bind_param("s", $rfid_tag);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo'];

        echo json_encode(["status" => "success", "message" => "Login bem-sucedido!", "user" => $user['nome']]);
    } else {
        echo json_encode(["status" => "error", "message" => "RFID não encontrado!"]);
    }
}
?>
