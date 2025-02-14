<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login RFID</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Passe a pulseira RFID no leitor</h2>
<input type="text" id="rfidInput" placeholder="Aguardando RFID..." readonly>

<script>
    $(document).ready(function() {
        let socket = new WebSocket("ws://localhost:8080"); // Conectar ao WebSocket

        socket.onmessage = function(event) {
            let rfid = event.data;
            $("#rfidInput").val(rfid);

            // Envia para o PHP para verificar o login
            $.post("rfid_login.php", { rfid: rfid }, function(response) {
                let data = JSON.parse(response);
                alert(data.message);
                if (data.status === "success") {
                    window.location.href = "dashboard.php";
                }
            });
        };
    });
</script>

</body>
</html>
