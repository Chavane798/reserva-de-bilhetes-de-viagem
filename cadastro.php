<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Conexão com o banco de dados (substitua com suas credenciais)
    $conexao = new mysqli("localhost", "root", "", "bilhetes");

    // Verifica se a conexão foi estabelecida corretamente
    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    // Query para inserir os dados na tabela de usuários
    $sql = "INSERT INTO usuarios (username, email, password) VALUES ('$username','$email', '$password')";

    if ($conexao->query($sql) === TRUE) {
        echo "Usuário cadastrado com sucesso!";
        header('location:login.html');
    } else {
        echo "Erro ao cadastrar usuário: " . $conexao->error;
    }

    // Fecha a conexão com o banco de dados
    
    $conexao->close();
}
?>
