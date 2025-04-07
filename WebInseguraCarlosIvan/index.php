<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ASIX2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexió fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM usuaris WHERE email = '$email' AND password = '$password'";
    echo "$sql";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['is_admin'] = $row['is_admin'];
        $_SESSION['id_u'] = $row['id_u'];
            
        if ($row['is_admin'] == 1) {
            header("Location: admin.php");
        } else {
            header("Location: client.php");
        }
        exit();
    } else {
        echo "<p>Usuari o contrasenya incorrectes</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Acceder</button>
        </form>
    </div>
</body>
</html>



