<?php

session_start();
require_once('conexao.php');

if (!isset($_SESSION['admin_logado'])) {
  header('Location:login.php');
  exit(); // se nao houver a permissão do usuario, irá parar o programa e nao aparecerá as demais informações.
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // retorna o metod usado para acessar a página.

  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];

  try {
    $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA)  VALUES (:nome, :email, :senha)";

    $stmt = $pdo->prepare($sql); //Nessa linha, $stmt é um objeto que representa a instrução SQL preparada. Você pode então vincular parâmetros a essa instrução e executá-la.
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
    $stmt->execute();
  } catch (PDOException $e) {
    echo "<p style='color=red;'> Erro ao cadastrar Usuário!" . $e->getMessage() . "</p>";
  }
}
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="icon" type="image/png" sizes="32x32" href="../img/logo.png">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/stars.css">
  <title>Login | Games Space </title>
</head>

<body>
  <header>
    <a href="../index.html"><img class="logo" src="../img/logo.png" alt="Foto da loja de Games"></a>
  </header>
  <div id="stars"></div>
  <div id="stars2"></div>
  <div id="stars3"></div>
  <div class="section">
    <div class="container">
      <div class="row full-height justify-content-center">
        <div class="col-12 text-center align-self-center py-5">
          <div class="section pb-5 pt-5 pt-sm-2 text-center">
            <h6 class="mb-0 pb-3"><span>Login</span><span>Registrar</span></h6>
            <input class="checkbox" type="checkbox" id="reg-log" name="reg-log" />
            <label for="reg-log"></label>
            <div class="card-3d-wrap mx-auto">
              <div class="card-3d-wrapper">
                <div class="card-front">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-4 pb-3">Administrador</h4>
                      <div class="form-group">
                        <form action="processa_login.php" method="post">
                          <input type="email" id="nome" name="nome" class="form-style" placeholder="Email" required>
                          <i class="input-icon uil uil-user"></i>
                      </div>
                      <div class="form-group mt-2">
                        <input type="password" id="senha" name="senha" class="form-style" placeholder="Senha" required>
                        <i class="input-icon uil uil-lock-alt"></i>
                      </div>

                      <input type="submit" class="btn mt-4" value="Entrar">

                      <?php
                      if (isset($_GET['erro'])) {
                        echo '<p style="color:red;">Nome de usuário ou senha incorretos!</p>';
                      }
                      ?>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="card-back">
                  <div class="center-wrap">
                    <div class="section text-center">
                      <h4 class="mb-3 pb-3">Cadastrar</h4>
                      <div class="form-group">

                        <form action="" method="post" enctype="multipart/form-data">

                          <input type="text" name="nome" id="nome" class="form-style" placeholder="Nome" required>
                          <i class="input-icon uil uil-user"></i>
                      </div>
                      <div class="form-group mt-2">
                        <input type="email" name="email" id="email" class="form-style" placeholder="Email" required>
                        <i class="input-icon uil uil-at"></i>
                      </div>
                      <div class="form-group mt-2">
                        <input type="password" name="senha" id="senha" step="0.01" class="form-style" placeholder="Senha" required>
                        <i class="input-icon uil uil-lock-alt"></i>
                      </div>
                      <button type="submit" class="btn mt-4">Cadastrar</button>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>