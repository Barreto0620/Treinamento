<?php 
	session_start();
	require_once('conexao.php');

	if(!isset($_SESSION['admin_logado'])){
		header('Location:login.php');
		exit(); 
	}

// bloco de consultas para buscar categorias. a variavem $categorias criada será utilizada no form para mostrar as categorias disponiveis.

try { 
	$stmt_categoria = $pdo->prepare("SELECT * FROM CATEGORIA");
	$stmt_categoria->execute();
	$categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "<p style='color=red;'> Erro ao buscar categorias" . $e->getMessage() . "</p>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){   //$_SERVER['REQUEST_METHOD'] retorna o metodo usado para acessar a pagina

	//criar um formulario com os nomes dessas variaveis
	$nome = $_POST['nome'];
	$descricao = $_POST['descricao'];
	$ativo = isset($_POST['ativo']) ? 1:0;

	try{
			$sql = "INSERT INTO CATEGORIA 
			(CATEGORIA_NOME, CATEGORIA_DESC, CATEGORIA_ID, CATEGORIA_ATIVO) 
			VALUES (:nome, :descricao, :categoria_id, :ativo)";

			$stmt = $pdo->prepare($sql);

			$stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
			$stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
			$stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_STR);
			$stmt->bindParam(':ativo', $ativo, PDO::PARAM_STR);
			$stmt->execute();
			
			//pegando o id do ultimo produto inserido
			$categoria_id = $pdo->lastInsertID();

	}catch (PDOException $e) {
			echo "<p style='color:red;'> Erro ao cadastrar o produto: ". $e->getMessage() ."</p>";
	}

	echo "<p style='color:green;'> Categoria cadastrada com sucesso!</p>";
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro de Categoria</title>
</head>
<body>
	<a href="painel_admin.php">👈 Voltar para o Painel do Administrador</a>
	<h2>Cadastrar Categoria</h2>

	<form action="" method="post" enctype="multipart/form-data">
		<label for="nome">Nome: </label>
		<input type="text" name="nome" id="nome" required> 
		<p>

		<label for="descricao">Descrição: </label>
		<textarea name="descricao" id="descricao" required> </textarea>
		<p>

		<label for="ativo">Ativo: </label>
		<input type="checkbox" name="ativo" id="ativo" value="1" checked> 
		<p>

		<button type="submit"> Cadastrar Produto</button>
<p></p>

	</form>
	<a href="cadastrar_produto.php">Cadastro de Produtos</a>
</body>
</html>
