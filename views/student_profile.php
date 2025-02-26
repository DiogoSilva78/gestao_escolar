<?php
include '../config/db.php';
include '../controllers/auth_check.php';

// Verifica se o usuário autenticado é um aluno
if ($_SESSION['user_type'] !== 'aluno') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Meu Perfil";
include '../includes/head.php';

// Buscar dados do aluno
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$aluno = $result->fetch_assoc();

// Definir valores padrão
$fotoPerfil = !empty($aluno['foto']) ? "../public/img/" . $aluno['foto'] : "../public/img/avatar.png";
?>

<div class="container mt-5">
    <h2 class="text-center text-primary">📋 Meu Perfil</h2>

    <div class="card shadow-lg p-4">
        <div class="text-center">
            <img src="<?= $fotoPerfil; ?>" class="rounded-circle border shadow-sm" width="150" alt="Foto do Aluno">
            <h3 class="mt-3"><?= htmlspecialchars($aluno['nome']); ?></h3>
        </div>

        <div class="mt-4">
            <p><strong>Email:</strong> <?= htmlspecialchars($aluno['email']); ?></p>
            <p><strong>Cartão de Cidadão:</strong> <?= htmlspecialchars($aluno['cartao_cidadao'] ?? 'Não disponível'); ?></p>
            <p><strong>Telefone:</strong> <?= htmlspecialchars($aluno['telefone'] ?? 'Não disponível'); ?></p>
            <p><strong>Morada:</strong> <?= htmlspecialchars($aluno['morada'] ?? 'Não disponível'); ?></p>
            <p><strong>Localidade:</strong> <?= htmlspecialchars($aluno['localidade'] ?? 'Não disponível'); ?></p>

            <?php if (!empty($aluno['encarregado_nome'])): ?>
                <h5 class="mt-4">👨‍👩‍👦 Encarregado de Educação</h5>
                <p><strong>Nome:</strong> <?= htmlspecialchars($aluno['encarregado_nome']); ?></p>
                <p><strong>Contato:</strong> <?= htmlspecialchars($aluno['encarregado_contato']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($aluno['encarregado_email']); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
