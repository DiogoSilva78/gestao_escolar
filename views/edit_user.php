<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

// Verifica se um ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php?error=ID do utilizador inválido.");
    exit();
}

$user_id = $_GET['id'];

// Buscar dados do utilizador
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_users.php?error=Utilizador não encontrado.");
    exit();
}

$user = $result->fetch_assoc();

// Buscar turmas disponíveis (caso seja aluno)
$turma_query = $conn->query("SELECT * FROM turmas");

// Lista de localidades para o `<select>`
$localidades = [
    "Amarante", "Baião", "Felgueiras", "Gondomar", "Lousada", "Maia", 
    "Matosinhos", "Marco de Canaveses", "Paços de Ferreira", "Paredes", 
    "Penafiel", "Porto", "Póvoa de Varzim", "Santo Tirso", "Trofa", 
    "Valongo", "Vila do Conde", "Vila Nova de Gaia"
];

// Processar formulário de edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];
    $tipo = $_POST['tipo'];
    $rfid_tag = $_POST['rfid_tag'] ?? null;
    $turma_id = (!empty($_POST['turma_id'])) ? $_POST['turma_id'] : null;
    $fotoPath = $user['foto'];

    // Campos adicionais
    $cartao_cidadao = $_POST['cartao_cidadao'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $contato_emergencia = $_POST['contato_emergencia'] ?? null;
    $encarregado_nome = $_POST['encarregado_nome'] ?? null;
    $encarregado_contato = $_POST['encarregado_contato'] ?? null;
    $encarregado_email = $_POST['encarregado_email'] ?? null;
    $morada = $_POST['morada'] ?? null;
    $localidade = $_POST['localidade'] ?? null;

    // Atualizar foto se um novo arquivo for enviado
    if (!empty($_FILES["foto"]["name"])) {
        $uploadDir = "../uploads/fotos/";
        $fotoName = basename($_FILES["foto"]["name"]);
        $fotoTmpName = $_FILES["foto"]["tmp_name"];
        $fotoSize = $_FILES["foto"]["size"];
        $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
        $fotoNewName = uniqid() . "." . $fotoExt;

        $allowedExts = ["jpg", "jpeg", "png", "gif"];
        if (in_array($fotoExt, $allowedExts) && $fotoSize < 5000000) { // Limite de 5MB
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            move_uploaded_file($fotoTmpName, $uploadDir . $fotoNewName);
            $fotoPath = "uploads/fotos/" . $fotoNewName;
        }
    }

    // Atualizar utilizador na base de dados
    $query = $conn->prepare("UPDATE users SET 
        nome = ?, email = ?, password = ?, tipo = ?, rfid_tag = ?, 
        cartao_cidadao = ?, telefone = ?, contato_emergencia = ?, 
        encarregado_nome = ?, encarregado_contato = ?, encarregado_email = ?, 
        morada = ?, localidade = ?, foto = ? WHERE id = ?");

    $query->bind_param("ssssssssssssssi", 
        $nome, $email, $password, $tipo, $rfid_tag, 
        $cartao_cidadao, $telefone, $contato_emergencia, 
        $encarregado_nome, $encarregado_contato, $encarregado_email, 
        $morada, $localidade, $fotoPath, $user_id);

    if ($query->execute()) {
        header("Location: manage_users.php?success=Utilizador atualizado com sucesso!");
        exit();
    } else {
        $error = "Erro ao atualizar utilizador.";
    }
}
?>

<?php 
$pageTitle = "Editar Utilizador"; 
include '../includes/head.php'; 
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">Editar Utilizador</h2>

    <form method="POST" enctype="multipart/form-data" class="card p-4 mx-auto shadow-lg" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha (deixe em branco para manter a atual)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select">
                <option value="aluno" <?= ($user['tipo'] === 'aluno') ? 'selected' : ''; ?>>Aluno</option>
                <option value="funcionario" <?= ($user['tipo'] === 'funcionario') ? 'selected' : ''; ?>>Funcionário</option>
                <option value="admin" <?= ($user['tipo'] === 'admin') ? 'selected' : ''; ?>>Administrador</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tag RFID</label>
            <input type="text" name="rfid_tag" class="form-control" value="<?= htmlspecialchars($user['rfid_tag'] ?? ""); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Localidade</label>
            <select name="localidade" class="form-select">
                <?php foreach ($localidades as $loc): ?>
                    <option value="<?= $loc; ?>" <?= ($user['localidade'] === $loc) ? 'selected' : ''; ?>><?= $loc; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto do Utilizador</label>
            <input type="file" name="foto" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
    </form>
</div>


<?php include '../includes/footer.php'; ?>
