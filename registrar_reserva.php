<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<a href="ver_reservas.php" class="btn btn-light">Ver Reservas</a>

<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redireciona para a página de login se o usuário não estiver logado
    exit;
}

// Verifica se o ID da viagem foi enviado através do POST
if(isset($_POST['id_viagem'])) {
    // Obtém o ID da viagem do formulário
    $id_viagem = $_POST['id_viagem'];
    
    // Verifica se o nome de usuário está definido na sessão
    if(isset($_SESSION['username'])) {
        // Obtém o nome de usuário da sessão
        $username = $_SESSION['username']; 

        // Conexão com o banco de dados
        $conexao = new mysqli("localhost", "root", "", "bilhetes");

        // Verifica se a conexão foi estabelecida corretamente
        if ($conexao->connect_error) {
            die("Erro de conexão: " . $conexao->connect_error);
        }

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

            // Prepara e executa a consulta SQL para inserir a reserva na tabela
            $sql_insert = "INSERT INTO reservas (id_viagem, id_usuario) VALUES (?, ?)";
            $stmt_insert = $conexao->prepare($sql_insert);
            $stmt_insert->bind_param("is", $id_viagem, $id_usuario);

            if ($stmt_insert->execute() === TRUE) {
                echo "Reserva efetuada com sucesso!";
                header("Location:ver_reservas.php");
            } else {
                echo "Erro ao efetuar a reserva: " . $conexao->error;
            }

            // Fecha a conexão com o banco de dados
            $stmt_insert->close();
        } else {
            echo "ID do usuário não encontrado.";
            
;        }

        // Fecha a conexão com o banco de dados
        $stmt->close();
        $conexao->close();
    } else {
        echo "Nome de usuário não encontrado na sessão.";
    }
} else {
    echo "ID da viagem não especificado.";
}

?>



</body>
</html>