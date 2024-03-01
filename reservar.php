<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se o usuário não estiver logado
    exit;
}

// Atribui o valor da sessão 'username' à variável $username_session
$username_session = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="bootstrap.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        grid-gap: 20px;
        text-align: center;
    }

    .card {
        background-color: #f0f0f0;
        padding: 70px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(202, 20, 20, 0.1);
    }

    .card h2 {
        margin-top: 0;
        color: #333;
    }

    .card p {
        color: #666;
    }
    
    .body {
        text-align: center;
    }
  </style>
  <title>Document</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Viagem😁</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
              <li class="nav-item">
                <a class="nav-link active" href="index.html">Home
                  <span class="visually-hidden">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="cadastro.html">Cadastro</a>
                  <a class="dropdown-item" href="login.html">Login</a>
                  <a class="dropdown-item" href="bilhetes.php">Something else here</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Separated link</a>
                </div>
              </li>
            </ul>
            <form class="d-flex">
              <input class="form-control me-sm-2" type="search" placeholder="Search">
              <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
        </div>
      </nav>
<div>           
    Bem-vindo, <?php echo $username_session; ?>
</div>

<div class="grid-container">
    <?php
    // Conexão com o banco de dados
    $servername = "localhost";
    $db_username = "root"; // Alterado para evitar conflito com a variável de sessão
    $db_password = "";
    $dbname = "bilhetes";

    // Cria a conexão
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta os dados da viagem no banco de dados
    $sql = "SELECT * FROM bilhetes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Exibe os dados da viagem em cada card
        while($row = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<h2>Viagem ID: ' . $row['id'] . '</h2>';
            echo '<p>Ponto de Partida: ' . $row['ponto_partida'] . '</p>';
            echo '<p>Ponto de Chegada: ' . $row['ponto_chegada'] . '</p>';
            echo '<p>Data de Saída: ' . $row['data_saida'] . '</p>';
            echo '<form method="post" action="registrar_reserva.php">';
            echo '<input type="hidden" name="id_viagem" value="' . $row['id'] . '">';
            echo '<input type="submit" name="reservar" class="btn btn-primary" value="Reservar">';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo "Nenhum resultado encontrado.";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
    ?>
</div>

</body>
</html>
