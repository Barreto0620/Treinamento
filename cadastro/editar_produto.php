<?php
session_start();

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

require_once('conexao.php');

$mensagem = '';
$produto = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $pdo->prepare("SELECT p.*, pi.IMAGEM_URL FROM PRODUTO p LEFT JOIN PRODUTO_IMAGEM pi ON p.PRODUTO_ID = pi.PRODUTO_ID WHERE p.PRODUTO_ID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        header('Location: listar_produtos.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $quantidade = $_POST['quantidade'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    // Processar a URL da imagem
    $imagem_url = $_POST['imagem_url'];

    try {
        $stmt = $pdo->prepare("UPDATE PRODUTO SET PRODUTO_NOME = :nome, PRODUTO_DESC = :descricao, PRODUTO_PRECO = :preco, CATEGORIA_ID = :categoria, PRODUTO_ATIVO = :ativo WHERE PRODUTO_ID = :id");
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_INT);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Atualização da URL da imagem
        $stmtImagem = $pdo->prepare("UPDATE PRODUTO_IMAGEM SET IMAGEM_URL = :imagem_url WHERE PRODUTO_ID = :id");
        $stmtImagem->bindParam(':imagem_url', $imagem_url, PDO::PARAM_STR);
        $stmtImagem->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtImagem->execute();

        // Atualização da quantidade
        $stmtQuantidade = $pdo->prepare('UPDATE PRODUTO_ESTOQUE SET PRODUTO_QTD = :quantidade WHERE PRODUTO_ID = :id');
        $stmtQuantidade->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
        $stmtQuantidade->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtQuantidade->execute();

        if ($stmt->rowCount() >= 0) {
            $mensagem = "Produto atualizado com sucesso!";
        }
        
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produtos</title>
</head>
<body>
<h2>Editar Produtos</h2>
<form action="editar_produto.php" method="post">
    <input type="hidden" name="id" value="<?php echo isset($produto['PRODUTO_ID']) ? $produto['PRODUTO_ID'] : ''; ?>">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" value="<?php echo isset($produto['PRODUTO_NOME']) ? $produto['PRODUTO_NOME'] : ''; ?>"><br>
    <label for="descricao">Descrição:</label>
    <textarea name="descricao" id="descricao"><?php echo isset($produto['PRODUTO_DESC']) ? $produto['PRODUTO_DESC'] : ''; ?></textarea><br>
    <label for="preco">Preço:</label>
    <input type="number" name="preco" id="preco" value="<?php echo isset($produto['PRODUTO_PRECO']) ? $produto['PRODUTO_PRECO'] : ''; ?>"><br>
    <label for="categoria">Categoria:</label>
    <select name="categoria" id="categoria">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM CATEGORIA");
        $stmt->execute();
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categorias as $cat) {
            echo "<option value='" . $cat['CATEGORIA_ID'] . "'";
            if (isset($produto['CATEGORIA_ID']) && $cat['CATEGORIA_ID'] == $produto['CATEGORIA_ID']) {
                echo " selected";
            }
            echo ">" . $cat['CATEGORIA_NOME'] . "</option>";
        }
        ?>
    </select><br>
    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" value="<?php echo isset($produto['PRODUTO_QTD']) ? $produto['PRODUTO_QTD'] : ''; ?>"><br>
    <label for="ativo">Ativo:</label>
    <input type="checkbox" name="ativo" id="ativo" <?php echo isset($produto['PRODUTO_ATIVO']) && $produto['PRODUTO_ATIVO'] == 1 ? 'checked' : ''; ?>><br>
    <label for="imagem_url">URL da Imagem:</label>
    <input type="text" name="imagem_url" id="imagem_url" value="<?php echo isset($produto['IMAGEM_URL']) ? $produto['IMAGEM_URL'] : ''; ?>"><br>
    <input type="submit" value="Atualizar Produto">
</form>

<p><?php echo $mensagem; ?></p>
<a href="listar_produtos.php">Voltar à Lista de Produtos</a>
</body>
</html>