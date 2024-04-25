<?php
session_start();

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

require_once('conexao.php');

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        // Excluir entradas relacionadas na tabela produto_imagem
        $stmtDeleteImagem = $pdo->prepare('DELETE FROM produto_imagem WHERE PRODUTO_ID = :id');
        $stmtDeleteImagem->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDeleteImagem->execute();

        // Excluir entradas relacionadas na tabela produto_estoque
        $stmtDeleteEstoque = $pdo->prepare('DELETE FROM produto_estoque WHERE PRODUTO_ID = :id');
        $stmtDeleteEstoque->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDeleteEstoque->execute();

        // Excluir o produto da tabela PRODUTO
        $stmtDeleteProduto = $pdo->prepare('DELETE FROM PRODUTO WHERE PRODUTO_ID = :id');
        $stmtDeleteProduto->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtDeleteProduto->execute();

        if ($stmtDeleteProduto->rowCount() > 0) {
            $mensagem = "Produto excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir o produto " . $id . "!";
        }
    } catch (PDOException $e) {
        echo "Erro ao executar operação: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Produto! </title>
</head>
<body>
    <h2> Deletar produto </h2>
    <p><?php echo $mensagem ?> </p>
    <a href="listar_produtos.php"> Voltar à listagem de produtos </a>
</body>
</html>
