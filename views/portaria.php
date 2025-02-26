<?php
include '../config/db.php';
$pageTitle = "Portaria - Registro de Entradas e Saídas";
include '../includes/head.php';

// Buscar os últimos 6 registros
$query = "SELECT p.*, u.nome FROM portaria p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.data_hora DESC LIMIT 6";
$result = $conn->query($query);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="../public/css/dashboard.css">

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
                    <li class="nav-item"><a class="nav-link" href="carregar_saldo.php">Carregar Saldo</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Registros da Portaria</h2>

    <!-- Campo para Capturar RFID -->
    <input type="text" id="rfidInput" class="form-control text-center mb-3 border-danger" 
           placeholder="Passe a pulseira RFID" style="font-size: 20px; text-align: center;" autofocus>

    <!-- Tabela de Últimos Registros -->
    <div class="table-responsive">
        <table class="table table-hover table-striped shadow-lg">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nome do Aluno</th>
                    <th>Tipo</th>
                    <th>Data e Hora</th>
                </tr>
            </thead>
            <tbody id="tabela-registros" class="text-center">
                <?php while ($registro = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($registro['nome']); ?></td>
                        <td>
                            <span class="badge <?= ($registro['tipo'] == 'entrada') ? 'bg-success' : 'bg-danger'; ?>">
                                <?= ucfirst($registro['tipo']); ?>
                            </span>
                        </td>
                        <td><?= date("d/m/Y H:i:s", strtotime($registro['data_hora'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- WebSocket e Atualização da Tabela -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let inputRfid = document.getElementById("rfidInput");

    // Captura RFID via WebSocket
    let socket = new WebSocket("ws://localhost:8080");

    socket.onmessage = function (event) {
        let rfid = event.data.trim();
        if (rfid !== "") {
            inputRfid.value = rfid;
            registrarEntradaSaida(rfid);
        }
    };

    socket.onerror = function (event) {
        console.error("Erro no WebSocket:", event);
    };

    // Captura RFID via teclado (se for manual)
    inputRfid.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            let rfid = inputRfid.value.trim();
            if (rfid !== "") {
                registrarEntradaSaida(rfid);
            }
        }
    });

    // Função para registrar entrada/saída via AJAX
    function registrarEntradaSaida(rfid) {
        fetch("../rfid/portaria_rfid.php", {  
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "rfid=" + encodeURIComponent(rfid)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Efeito visual de sucesso
                inputRfid.classList.remove("border-danger");
                inputRfid.classList.add("border-success");
                setTimeout(() => inputRfid.classList.remove("border-success"), 1000);

                // Atualizar tabela
                atualizarRegistros();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Erro na requisição:", error));

        // Limpa o campo após a leitura
        inputRfid.value = "";
    }

    // Atualiza os registros na tabela sem recarregar a página
    function atualizarRegistros() {
        fetch("../rfid/get_portaria.php")
            .then(response => response.text())
            .then(html => {
                document.getElementById("tabela-registros").innerHTML = html;
            })
            .catch(error => console.error("Erro ao atualizar registros:", error));
    }

    // Atualiza a tabela a cada 10 segundos automaticamente
    setInterval(atualizarRegistros, 10000);
});
</script>

<?php include '../includes/footer.php'; ?>
