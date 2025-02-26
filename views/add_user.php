<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

// Procurar turmas disponíveis
$turma_query = $conn->query("SELECT id, nome FROM turmas");

// Pasta onde as fotos serão armazenadas
$uploadDir = "../public/img/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];
    $rfid_tag = $_POST['rfid_tag'] ?? null;
    $turma_id = ($_POST['turma_id'] != '') ? $_POST['turma_id'] : null;

    // Campos adicionais
    $cartao_cidadao = $_POST['cartao_cidadao'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $contato_emergencia = $_POST['contato_emergencia'] ?? null;
    $encarregado_nome = $_POST['encarregado_nome'] ?? null;
    $encarregado_contato = $_POST['encarregado_contato'] ?? null;
    $encarregado_email = $_POST['encarregado_email'] ?? null;
    $morada = $_POST['morada'] ?? null;
    $localidade = $_POST['localidade'] ?? null;

    // Upload da Foto
    $fotoNome = null;
    if (!empty($_FILES['foto']['name'])) {
        $fotoNome = time() . "_" . basename($_FILES['foto']['name']);
        $fotoCaminho = $uploadDir . $fotoNome;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $fotoCaminho)) {
            $fotoNome = null;
        }
    }

    // Inserir utilizador na base de dados
    $query = $conn->prepare("INSERT INTO users (nome, email, password, tipo, rfid_tag, cartao_cidadao, telefone, contato_emergencia, encarregado_nome, encarregado_contato, encarregado_email, morada, localidade, foto) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssssssssssssss", $nome, $email, $password, $tipo, $rfid_tag, $cartao_cidadao, $telefone, $contato_emergencia, $encarregado_nome, $encarregado_contato, $encarregado_email, $morada, $localidade, $fotoNome);

    if ($query->execute()) {
        $user_id = $query->insert_id;

        // Se for aluno, associar à turma
        if ($tipo === 'aluno' && $turma_id) {
            $aluno_query = $conn->prepare("INSERT INTO alunos (user_id, turma_id) VALUES (?, ?)");
            $aluno_query->bind_param("ii", $user_id, $turma_id);
            $aluno_query->execute();
        }

        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Erro ao adicionar utilizador.";
    }
}
?>

<?php
$pageTitle = "Adicionar Utilizador";
include '../includes/head.php';
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">Adicionar Utilizador</h2>

    <form method="POST" enctype="multipart/form-data" class="card p-4 mx-auto shadow-lg" style="max-width: 500px;">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">RFID Tag</label>
            <input type="text" name="rfid_tag" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo de Utilizador</label>
            <select name="tipo" class="form-select" required>
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
                <option value="funcionario">Funcionário</option>
                <option value="admin">Administrador</option>
            </select>
        </div>


        <!-- Campos adicionais apenas para Alunos -->
        <div id="camposAluno" style="display: none;">
            <div class="mb-3">
                <label class="form-label">Turma</label>
                <select name="turma_id" class="form-select">
                    <option value="">Selecione uma turma</option>
                    <?php while ($turma = $turma_query->fetch_assoc()): ?>
                        <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Cartão de Cidadão</label>
                <input type="text" name="cartao_cidadao" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Número de Telefone</label>
                <input type="text" name="telefone" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Contato de Emergência</label>
                <input type="text" name="contato_emergencia" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Nome do Encarregado de Educação</label>
                <input type="text" name="encarregado_nome" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Contato do Encarregado de Educação</label>
                <input type="text" name="encarregado_contato" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail do Encarregado de Educação</label>
                <input type="email" name="encarregado_email" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Morada</label>
                <textarea name="morada" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Localidade</label>
                <select name="localidade" class="form-select">
                    <option value="Amarante">Amarante</option>
                    <option value="Baião">Baião</option>
                    <option value="Felgueiras">Felgueiras</option>
                    <option value="Gondomar">Gondomar</option>
                    <option value="Lousada">Lousada</option>
                    <option value="Maia">Maia</option>
                    <option value="Matosinhos">Matosinhos</option>
                    <option value="Marco de Canaveses">Marco de Canaveses</option>
                    <option value="Paços de Ferreira">Paços de Ferreira</option>
                    <option value="Paredes">Paredes</option>
                    <option value="Penafiel">Penafiel</option>
                    <option value="Porto">Porto</option>
                    <option value="Póvoa de Varzim">Póvoa de Varzim</option>
                    <option value="Santo Tirso">Santo Tirso</option>
                    <option value="Trofa">Trofa</option>
                    <option value="Valongo">Valongo</option>
                    <option value="Vila do Conde">Vila do Conde</option>
                    <option value="Vila Nova de Gaia">Vila Nova de Gaia</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100">Adicionar</button>
    </form>
</div>


<script>
    function mostrarCamposAluno() {
        document.getElementById("camposAluno").style.display = document.getElementById("tipoUsuario").value === "aluno" ? "block" : "none";
    }
    document.addEventListener("DOMContentLoaded", mostrarCamposAluno);
</script>

<?php include '../includes/footer.php'; ?>