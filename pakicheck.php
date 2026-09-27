<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    header("Location: index.php?error=" . urlencode("All credentials must be provided."));
    exit();
}

$stmt = $conn->prepare("SELECT id, firstname, lastname, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($password === $row['password'] || password_verify($password, $row['password'])) {
        $_SESSION['user_id']   = $row['id'];
        $_SESSION['firstname'] = $row['firstname'];
        $_SESSION['lastname']  = $row['lastname'];
        $_SESSION['username']  = $row['username'];

        $stmt->close();
        $conn->close();
        header("Location: dashboard.php");
        exit();
    }
}

$stmt->close();
$conn->close();

header("Location: index.php?error=" . urlencode("Invalid username or password."));
exit();
?>