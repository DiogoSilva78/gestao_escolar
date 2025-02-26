<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Apenas funcionários podem aceder
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

        // Registrar transação
        $log_transacao = $conn->prepare("INSERT INTO transacoes (user_id, tipo, valor) VALUES (?, 'carregamento', ?)");
        $log_transacao->bind_param("id", $aluno['id'], $valor);
        $log_transacao->execute();

        echo json_encode(["status" => "success", "message" => "Saldo carregado com sucesso!", "saldo" => $novo_saldo]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aluno não encontrado!"]);
    }

    exit();
}
?>

<?php
$pageTitle = "Carregar Saldo";
include '../includes/head.php';
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard_funcionario.php">
                <img src="../public/img/logo-epbjc.png" alt="Logo" width="35" height="35" class="me-2">
                <span>School Bracelet</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard_funcionario.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="comprar_bar.php">Bar</a></li>
                    <li class="nav-item"><a class="nav-link" href="comprar_papelaria.php">Papelaria</a></li>
                    <li class="nav-item"><a class="nav-link" href="portaria.php">Portaria</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="text-center text-primary">Carregar Saldo</h2>

    <div class="card shadow-sm p-4 mx-auto" style="max-width: 400px;">
        <div class="mb-3 text-center">
            <i class="bi bi-credit-card text-primary" style="font-size: 3rem;"></i>
        </div>
        <div class="mb-3">
            <label class="form-label">Passe a Pulseira</label>
            <input type="text" id="rfidInput" class="form-control" placeholder="Aproxime a pulseira" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Valor (€)</label>
            <input type="number" id="valor" class="form-control" placeholder="Insira o valor" required>
        </div>
        <button class="btn btn-success w-100" onclick="carregarSaldo()">Carregar</button>
    </div>
</div>

<script>
    // Conectar ao WebSocket do servidor que recebe o RFID
    let socket = new WebSocket("ws://localhost:8080");

    socket.onmessage = function(event) {
        let rfidTag = event.data.trim();
        document.getElementById("rfidInput").value = rfidTag;
    };

    function carregarSaldo() {
        let rfid = document.getElementById("rfidInput").value;
        let valor = document.getElementById("valor").value;

        if (!rfid || !valor) {
            alert("⚠️ Preencha todos os campos antes de carregar o saldo.");
            return;
        }

        fetch("carregar_saldo.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "rfid=" + encodeURIComponent(rfid) + "&valor=" + encodeURIComponent(valor)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                document.getElementById("valor").value = "";
                document.getElementById("rfidInput").value = "";
            }
        })
        .catch(error => console.error("Erro ao carregar saldo:", error));
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include '../includes/footer.php'; ?>
