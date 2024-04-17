<?php
session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

require_once('conexao.php');


if ($_SERVER['REQUEST_METHOD'] == 'GET') { 
    
    if (isset($_GET['id'])) { 
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT * FROM ADMINISTRADOR WHERE ADM_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();
            $administrador = $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        header('Location: listar_admin.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("UPDATE ADMINISTRADOR SET ADM_NOME = :nome, ADM_EMAIL = :email, ADM_SENHA = :senha, ADM_ATIVO = :ativo WHERE ADM_ID = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: listar_admin.php');
        exit();
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Informações do Administrador</title>
</head>
<body>
<h2>Editar Administrador</h2>
<form action="editar_admin.php" method="post">
    <input type="hidden" name="id" value="<?php echo $administrador['ADM_ID']; ?>">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" value="<?php echo $administrador['ADM_NOME']; ?>"><br>
    <label for="email">E-mail:</label>
    <input  type="email" name="email" id="email"value="<?php echo $administrador['ADM_EMAIL']; ?>" ><br>
    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" value="<?php echo $administrador['ADM_SENHA']; ?>"><br>
    <label for="ativo">Administrador Ativo:</label>
    <input type="checkbox" name="ativo" id="ativo" value="<?php echo $administrador['ADM_ATIVO']; ?>"><br>

    <button type="submit">Editar</button>
    <p>
    <a href="listar_admin.php">Voltar à Lista de Administradores</a>
</form>

</body>
</html>