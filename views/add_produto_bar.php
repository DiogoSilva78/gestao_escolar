<?php
include '../config/db.php';
$adminOnly = true;
include '../controllers/auth_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $stock = $_POST['stock'];

    // Diretório para salvar a imagem
    $targetDir = "../uploads/produtos/";
    $imagemNome = basename($_FILES["imagem"]["name"]);
    $imagemCaminho = $targetDir . $imagemNome;
    $uploadOk = 1;

    // Verificar se é uma imagem real
    $check = getimagesize($_FILES["imagem"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        $error = "O arquivo não é uma imagem válida.";
    }

    // Verificar tamanho do arquivo (máx. 2MB)
    if ($_FILES["imagem"]["size"] > 2000000) {
        $uploadOk = 0;
        $error = "O tamanho da imagem excede 2MB.";
    }

    // Extensões permitidas
    $imageFileType = strtolower(pathinfo($imagemCaminho, PATHINFO_EXTENSION));
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $uploadOk = 0;
        $error = "Apenas formatos JPG, JPEG, PNG e GIF são permitidos.";
    }

    // Se estiver tudo certo, faz o upload
    if ($uploadOk && move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagemCaminho)) {
        // Inserir na base de dados
        $query = $conn->prepare("INSERT INTO produtos (nome, preco, stock, categoria, imagem) VALUES (?, ?, ?, 'papelaria', ?)");
        $query->bind_param("sdis", $nome, $preco, $stock, $imagemNome);

        if ($query->execute()) {
            header("Location: manage_papelaria.php");
            exit();
        } else {
            $error = "Erro ao adicionar produto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php 
    $pageTitle = "Adicionar Produto - Papelaria";
    include '../includes/head.php'; 
    ?>
</head>
<body>

<h2>Adicionar Produto - Papelaria</h2>
<a href="manage_papelaria.php">Voltar</a>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post" enctype="multipart/form-data">
    Nome: <input type="text" name="nome" required><br>
    Preço (€): <input type="number" step="0.01" name="preco" required><br>
    Stock: <input type="number" name="stock" required><br>
    Imagem: <input type="file" name="imagem" accept="image/*" required><br>
    <input type="submit" value="Adicionar">
</form>

<?php include '../includes/footer.php'; ?>

</body>
</html>