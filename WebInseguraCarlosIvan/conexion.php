<?php
// credencials per establir connexió amb la bbdd
$host = "localhost"; 
$dbname = "asix2";
$username = "root";
$password = "";

// Establim connexió
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de connexió: " . $conn->connect_error);
}
?>