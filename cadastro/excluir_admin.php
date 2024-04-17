<?php
    session_start();

    if(!isset($_SESSION['admin_logado'])){
        header("Location:login.php");
        exit();
    }

    require_once('conexao.php');
    
    $mensagem = '';

    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])){
        $id = $_GET['id'];
        try{
            $stmt = $pdo->prepare('DELETE FROM ADMINISTRADOR WHERE ADM_ID = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                $mensagem = "Usuário excluido com sucesso!";
            } else {
                $mensagem = "Erro ao excluir Usuário!" . $id . " !";
            }
        } catch (PDOException $e){
            echo "Erro ao executar operação: " . $e;
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Produto </title>
</head>
<body>
    <h2> Deletar</h2>
    <p><?php echo $mensagem ?> </p>
    <a href="listar_admin.php"> Voltar à listagem de Administradores </a>
</body>
</html>