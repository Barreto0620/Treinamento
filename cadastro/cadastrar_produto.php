<?php

session_start();

require_once("conexao.php");

if (!isset($_SESSION['admin_logado'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
    $stmt_categoria->execute();
    $categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro:" . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $desconto = $_POST['desconto'];
    $categoria_id = $_POST['categoria_id'];
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    $imagens = $_POST['imagem_url'];
    $imagem_ordens = $_POST['imagem_ordem'];

    try {
        $sql = "INSERT INTO PRODUTO (PRODUTO_NOME, PRODUTO_DESC, PRODUTO_PRECO, PRODUTO_DESCONTO, CATEGORIA_ID, PRODUTO_ATIVO, IMAGEM_URL) VALUES (:nome, :descricao, :preco, :desconto, :categoria_id, :ativo, :imagens)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':preco', $preco, PDO::PARAM_STR);
        $stmt->bindParam(':desconto', $desconto, PDO::PARAM_STR);
        $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_STR);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_STR);
        $stmt->execute();
        $produto_id = $pdo->lastInsertId(); /* Atenção */

        foreach($imagem_urls as $index => $url){
            $ordem = $imagem_ordens[$index];
            $sql_imagem = "INSERT INTO PRODUTO_IMAGEM(IMAGEM_URL, PRODUTO_ID, IMAGEM_ORDEM ) VALUES (:imagens, :produto_id, :ordem_imagem)";
            $stmt_imagem = $pdo->prepare($sql_imagem);
            $stmt_imagem->bindParam(':imagem_url', $imagem_url, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':ordem_imagem', $ordem, PDO::PARAM_INT);
            $stmt_imagem->execute();}
        echo "<p style='color:green;'> Produto cadastrado com sucesso! </p>";
    }catch (PDOException $e) {
        echo "<p style='color=red;'> Erro ao cadastrar o produto" . $e->getMessage() . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
</head>

<body>
    <h2>Cadastrar Produto</h2>

    <script>
        function adicionarImagem() {
            const containerImagens = document.getElementById('containerImagens');
            const novoDiv = document.createElement('div');
            novoDiv.className = 'imagem-input';

            const novoInputURL =document.createElement('input');
            novoInputURL.type = 'text';
            novoInputURL.name = 'imagem_url[]';
            novoInputURL.placeholder = 'URL da Imagem';
            novoInputURL.required = true;

            const novoInputOrdem =document.createElement('input');
            novoInputOrdem.type = 'number';
            novoInputOrdem.name = 'imagem_ordem[]';
            novoInputOrdem.placeholder = 'Ordem da Imagem';
            novoInputOrdem.min = '1';
            novoInputOrdem.required = true;

            novoDiv.appendChild(novoInputURL);
            novoDiv.appendChild(novoInputOrdem);

            containerImagens.appendChild(novoDiv);

            const novoInput = document.createElement('input');
            novoInput.type = 'text';
            novoInput.name = 'imagem_url[]';
            novoInput.required = true;
            containerImagens.appendChild(novoInput);
        }
    </script>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <p>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required></textarea>
        <p>
            <label for="preco">Preço:</label>
            <input type="number" name="preco" id="preco" step="0.01" required>
        <p>
            <label for="desconto">Desconto:</label>
            <input type="number" name="desconto" id="desconto" step="0.01">
        <p>
            <label for="categoria_id">Categoria:</label>
            <select name="categoria_id" id="categoria_id" required>
        
                <?php

                foreach ($categorias as $categoria) :

                ?>
                    <option value="<?= $categoria['CATEGORIA_ID'] ?>">
                        <?= $categoria['CATEGORIA_NOME'] ?></option>
                <?php endforeach; ?>
            </select>
            <label for="ativo">Ativo:</label>
            <input type="checkbox" name="ativo" id="ativo" value="1" checked>
        <p>
        <div id="containerImagens">
            <label for="imagem">Imagem URL:</label>
            <input type="text" name="imagem_url[]" placeholder="URL da imagem" required>
        </div>
        <button type="button" onclick="adicionarImagem()">Adicionar Mais Imagens</button>
        <p>
        <label for="imagem_ordem">Ordem da Imagem:</label>
            <input type="number" name="imagem_ordem" id="imagem_ordem" min="1" required>
            <p>
            <button type="submit">Cadastrar Produto</button>
    </form>
    <p>
        <a href="painel_admin.php">Voltar para o Painel do Administrador</a>
        <p>
        <a href="listar_produtos.php">Lista de Produtos</a>


</body>

</html>