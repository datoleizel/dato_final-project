<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "phpcrudleizel";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database Connection Error: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>