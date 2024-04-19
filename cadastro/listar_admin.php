<?php
session_start();

require_once("conexao.php");
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM administrador");
    $stmt->execute();
    $administrador = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro:" . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/lista_admin.css">
    <title>Administradores Cadastrados</title>
</head>

<body>
    <h1>Lista de Administradores</h1>
    <a href="painel_admin.php">👈 Voltar para o Painel do Administrador</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Senha</th>
                <th>Ativo</th>
                <th>Editar</th>
                <th>Excluir</th>
            </tr>
        </thead>

        <?php foreach ($administrador as $administrador) : ?>
            <tr>
                <td><?php echo $administrador['ADM_ID']; ?></td>
                <td><?php echo $administrador['ADM_NOME']; ?></td>
                <td><?php echo $administrador['ADM_EMAIL']; ?></td>
                <td><?php echo $administrador['ADM_SENHA']; ?></td>
                <td><?php echo $administrador['ADM_ATIVO']; ?></td>
                <td><a href="editar_admin.php?id=<?php echo $administrador['ADM_ID']; ?>"class="action-btn">✍</a></td>
                <td><a href="excluir_admin.php?id=<?php echo $administrador['ADM_ID']; ?>" class="action-btn delete-btn">❌</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>