<?php
include '../config/db.php';
include '../controllers/auth_check.php';

if ($_SESSION['user_type'] !== 'funcionario') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'];
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    // Buscar o aluno pelo RFID
    $query = $conn->prepare("SELECT id, nome, saldo FROM users WHERE rfid_tag = ? AND tipo = 'aluno'");
    $query->bind_param("s", $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $aluno = $result->fetch_assoc();
        $aluno_id = $aluno['id'];

        // Buscar o produto no bar
        $query_produto = $conn->prepare("SELECT nome, preco, stock FROM produtos WHERE id = ? AND categoria = 'bar'");
        $query_produto->bind_param("i", $produto_id);
        $query_produto->execute();
        $produto = $query_produto->get_result()->fetch_assoc();

        $total = $produto['preco'] * $quantidade;

        if ($aluno['saldo'] >= $total && $produto['stock'] >= $quantidade) {
            // Registrar a compra na tabela transacoes
            $log_transacao = $conn->prepare("INSERT INTO transacoes (user_id, tipo, valor, data) VALUES (?, 'compra', ?, NOW())");
            $log_transacao->bind_param("id", $aluno_id, $total);
            $log_transacao->execute();
            $transacao_id = $log_transacao->insert_id;

            // Registrar os itens comprados
            $log_itens = $conn->prepare("INSERT INTO transacoes_itens (transacao_id, produto_id, quantidade) VALUES (?, ?, ?)");
            $log_itens->bind_param("iii", $transacao_id, $produto_id, $quantidade);
            $log_itens->execute();

            // Subtrair saldo do aluno
            $novo_saldo = $aluno['saldo'] - $total;
            $update_saldo = $conn->prepare("UPDATE users SET saldo = ? WHERE id = ?");
            $update_saldo->bind_param("di", $novo_saldo, $aluno_id);
            $update_saldo->execute();

            // Atualizar estoque do produto
            $novo_stock = $produto['stock'] - $quantidade;
            $update_stock = $conn->prepare("UPDATE produtos SET stock = ? WHERE id = ?");
            $update_stock->bind_param("ii", $novo_stock, $produto_id);
            $update_stock->execute();

            echo json_encode(["status" => "success", "message" => "Compra realizada com sucesso!", "novo_saldo" => $novo_saldo]);
        } else {
            echo json_encode(["status" => "error", "message" => "Saldo ou estoque insuficiente!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Aluno não encontrado!"]);
    }
    exit();
}
?>



<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
    $pageTitle = "Painel do Administrador"; // Define o título da página dinamicamente
    include '../includes/head.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
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
                    <li class="nav-item"><a class="nav-link" href="comprar_papelaria.php">Papelaria</a></li>
                    <li class="nav-item"><a class="nav-link" href="carregar_saldo.php">Carregar Saldo</a></li>
                    <li class="nav-item"><a class="nav-link" href="portaria.php">Portaria</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white"
                            href="../controllers/logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h2>Comprar na Papelaria</h2>
    <input type="text" id="rfidInput" placeholder="Passe a pulseira" readonly>
    <select id="produto">
        <?php
        $query = "SELECT id, nome, preco FROM produtos WHERE categoria = 'bar'";
        $result = $conn->query($query);

        while ($produto = $result->fetch_assoc()) {
            echo "<option value='{$produto['id']}'>{$produto['nome']} (€" . number_format($produto['preco'], 2, ',', '.') . ")</option>";
        }
        ?>
    </select>

    <input type="number" id="quantidade" placeholder="Quantidade">
    <button onclick="comprarProduto()">Comprar</button>

    <script>
        function comprarProduto() {
            let rfid = $("#rfidInput").val();
            let produto_id = $("#produto").val();
            let quantidade = $("#quantidade").val();

            $.post("comprar_bar.php", { rfid: rfid, produto_id: produto_id, quantidade: quantidade }, function (response) {
                let data = JSON.parse(response);
                alert(data.message);
            });
        }

        // Simulação do WebSocket para ler RFID
        document.addEventListener("keydown", function (event) {
            if (event.key === "Enter") {
                $("#rfidInput").val("123456789"); // Simula leitura RFID
            }
        });

        $(document).ready(function () {
            let socket = new WebSocket("ws://localhost:8080");

            socket.onmessage = function (event) {
                let rfid = event.data.trim();
                $("#rfidInput").val(rfid);
            };

            socket.onerror = function (event) {
                console.error("Erro no WebSocket: ", event);
            };
        });

    </script>

<?php include '../includes/footer.php'; ?>


</body>

</html>