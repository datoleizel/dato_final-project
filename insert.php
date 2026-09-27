<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit;
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    header("Location: dashboard.php?error=" . urlencode("Security token invalid."));
    exit;
}

$firstname = trim($_POST['firstname'] ?? '');
$lastname  = trim($_POST['lastname'] ?? '');

if ($firstname === '' || $lastname === '' || strlen($firstname) > 50 || strlen($lastname) > 50) {
    header("Location: dashboard.php?error=" . urlencode("Please enter valid first and last names."));
    exit;
}

$stmt = $conn->prepare("INSERT INTO students (firstname, lastname) VALUES (?, ?)");
$stmt->bind_param("ss", $firstname, $lastname);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: dashboard.php?success=" . urlencode("Student registered successfully."));
    exit;
}

$stmt->close();
$conn->close();
header("Location: dashboard.php?error=" . urlencode("Failed to register student."));
exit;
?>