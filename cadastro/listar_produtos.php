<?php
session_start();

require_once("conexao.php");
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT PRODUTO.*, CATEGORIA.CATEGORIA_NOME, PRODUTO_IMAGEM.IMAGEM_URL, PRODUTO_ESTOQUE.PRODUTO_QTD FROM PRODUTO JOIN CATEGORIA ON PRODUTO.CATEGORIA_ID = CATEGORIA.CATEGORIA_ID LEFT JOIN PRODUTO_IMAGEM ON PRODUTO.PRODUTO_ID = PRODUTO_IMAGEM.PRODUTO_ID LEFT JOIN PRODUTO_ESTOQUE ON PRODUTO.PRODUTO_ID = PRODUTO_ESTOQUE.PRODUTO_ID");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color=red;'> Erro ao listar os produtos" . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Cadastrados</title>

    <style>
body{
    font-family: Arial, sans-serif;
}

table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td{
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th{
    background-color: #4CAF50;
    color: white;
}

tr:hover{
    background-color: #f1f1f1;
}

.action-btn{
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}

.action-btn:hover{
    background-color: #45a049;
}

.action-btn .delete-btn{
    background-color: #f44336;
}

.action-btn .delete-btn:hover{
    background-color: #da190b;
}
</style>

</head>

<body>
    <h1>Lista de Produtos</h1>
    <a href="cadastrar_produto.php">👈 Voltar</a>
    <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Ativo</th>
                <th>Desconto</th>
                <th>Estoque</th>
                <th>Imagem</th>
                <th>Ações</th>
                <th></th>
            </tr>

        <?php foreach ($produtos as $produto) : ?>
            <tr>
                <td><?php echo $produto['PRODUTO_ID']; ?></td>
                <td><?php echo $produto['PRODUTO_NOME']; ?></td>
                <td><?php echo $produto['PRODUTO_DESC']; ?></td>
                <td><?php echo $produto['PRODUTO_PRECO']; ?></td>
                <td><?php echo $produto['CATEGORIA_NOME']; ?></td>
                <td><?php echo $produto['PRODUTO_ATIVO'] == 1 ? 'Sim' : 'Não'; ?></td>
                <td><?php echo $produto['PRODUTO_DESCONTO']; ?></td>
                <td><?php echo $produto['PRODUTO_QTD']; ?></td>
                <td><?php echo $produto['IMAGEM_URL'];?> alt="<?php echo $produto['IMAGEM_URL']; ?></td>
                <td><a href="editar_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="action-btn">✍</a></td>
                <td><a href="excluir_produto.php?id=<?php echo $produto['PRODUTO_ID']; ?>" class="action-btn delete-btn">❌</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="cadastrar_produto.php">Voltar para o Cadastro</a>
</body>

</html> 