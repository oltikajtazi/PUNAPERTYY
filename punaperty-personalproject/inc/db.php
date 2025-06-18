<?php
$host = 'localhost';
$user = 'root';      
$pass = '';
$db = 'dboltii';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
