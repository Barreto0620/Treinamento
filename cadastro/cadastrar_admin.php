<?php 
session_start();
require_once('conexao.php');

if(!isset($_SESSION['admin_logado'])){
	header('Location:login.php');
	exit(); // se nao houver a permissão do usuario, irá parar o programa e nao aparecerá as demais informações.
}

if($_SERVER['REQUEST_METHOD'] == 'POST' ) { // retorna o metod usado para acessar a página.

	$nome = $_POST ['nome'];
	$email = $_POST ['email'];
	$senha = $_POST ['senha'];
	$ativo = isset($_POST ['ativo']) ? 1 : 0;

try {
	$sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO)  VALUES (:nome, :email, :senha, :ativo)";

	$stmt = $pdo->prepare($sql); //Nessa linha, $stmt é um objeto que representa a instrução SQL preparada. Você pode então vincular parâmetros a essa instrução e executá-la.
	$stmt->bindParam(':nome',$nome,PDO::PARAM_STR);
	$stmt->bindParam(':email',$email,PDO::PARAM_STR);
	$stmt->bindParam(':senha',$senha,PDO::PARAM_STR);
	$stmt->bindParam(':ativo',$ativo,PDO::PARAM_INT);
	$stmt->execute();

	echo "<p style='color:green;'> Administrador cadastrado com sucesso! </p>";
	}catch (PDOException $e) {
		echo "<p style='color=red;'> Erro ao cadastrar Usuário!" . $e->getMessage() . "</p>";
	}
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro de Administrador</title>
</head>
<body>
	<a href="painel_admin.php">👈 Voltar para o Painel do Administrador</a>
	<h2>Cadastrar</h2>

	<form action="" method="post" enctype="multipart/form-data">
		<label for="nome">Nome: </label>
		<input type="text" name="nome" id="nome" required> 
		<p>

		<label for="email">E-mail: </label>
		<input type="email" name="email" id="email" required>
		<p>

		<label for="senha">Senha: </label>
		<input type="password" name="senha" id="senha" step="0.01" required> 
		<p>

		<label for="ativo">Ativo: </label>
		<input type="checkbox" name="ativo" id="ativo" value="1" checked> 
		<p>

		<button type="submit">Cadastrar</button>
	</form>

	<a href="listar_admin.php"> Lista de Administradores</a>
</body>
</html>