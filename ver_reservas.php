<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <script>
    function goBack() {
        window.history.back();
    }
</script>
    <title>Document</title>
</head>
<body>
<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se o usuário não estiver logado
    exit;
}

// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "bilhetes");

// Verifica se a conexão foi estabelecida corretamente
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Obtém o nome de usuário da sessão
$username = $_SESSION['username']; 

// Prepara e executa a consulta SQL para obter o ID do usuário
$sql = "SELECT id FROM usuarios WHERE username=?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o resultado foi obtido com sucesso
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id_usuario = $row['id'];

    // Prepara e executa a consulta SQL para obter as reservas do usuário
    $sql_reservas = "SELECT * FROM reservas WHERE id_usuario = ?";
    $stmt_reservas = $conexao->prepare($sql_reservas);
    $stmt_reservas->bind_param("i", $id_usuario);
    $stmt_reservas->execute();
    $result_reservas = $stmt_reservas->get_result();

    // Verifica se o usuário possui reservas
    if ($result_reservas->num_rows > 0) {
        echo "<h2>Suas Reservas</h2>";
        echo "<table class='table table-hover'>";
        echo "<thead><tr><th scope='col'>ID da Reserva</th><th scope='col'>ID da Viagem</th><th scope='col'>Data e Hora da Reserva</th></tr></thead>";
        echo "<tbody>";
        
        // Exibe as reservas do usuário
        while ($row_reserva = $result_reservas->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row_reserva['id'] . "</td>";
            echo "<td>" . $row_reserva['id_viagem'] . "</td>";
            echo "<td>" . $row_reserva['data_reserva'] . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "Você ainda não fez nenhuma reserva.";
    }

    // Fecha as consultas e a conexão com o banco de dados
    $stmt_reservas->close();
} else {
    echo "ID do usuário não encontrado.";
}

$stmt->close();
$conexao->close();
?>

<button type="button" class="btn btn-link" onclick="goBack()">Voltar</button>


</body>
</html>