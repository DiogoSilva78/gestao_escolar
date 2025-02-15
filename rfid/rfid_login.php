<?php
include '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid_tag = $_POST['rfid'];

    $query = $conn->prepare("SELECT * FROM users WHERE rfid_tag = ?");
    $query->bind_param("s", $rfid_tag);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo']; // Guarda o tipo de usuário

        // Verifica se é admin ou aluno e define a URL de redirecionamento
        $redirect_url = ($user['tipo'] == 'admin') ? "dashboard_admin.php" : "dashboard_alunos.php";

        echo json_encode(["status" => "success", "message" => "Login bem-sucedido!", "redirect" => $redirect_url]);
    } else {
        echo json_encode(["status" => "error", "message" => "RFID não encontrado!"]);
    }
}
?>
