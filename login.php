<?php
session_start();

// Verifica se o usuário enviou o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Conexão com o banco de dados
    $conexao = new mysqli("localhost", "root", "", "bilhetes");

    // Verifica se a conexão foi estabelecida corretamente
    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    // Prepara a consulta SQL para evitar injeção de SQL
    $sql = "SELECT * FROM usuarios WHERE username=? AND password=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($resultado->num_rows == 1) {
        // Define a variável de sessão para o nome de usuário
        $_SESSION['username'] = $username;
        // Redireciona para a página principal após o login bem-sucedido
        header("Location: reservar.php");
        exit;
    } else {
        // Mensagem de erro se o nome de usuário ou senha estiverem incorretos
        echo "Nome de usuário ou senha incorretos.";
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conexao->close();
}
?>
<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se o usuário não estiver logado
    exit;
}

$username_session = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="bootstrap.css">
  <title>Document</title>
</head>
<body>

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
