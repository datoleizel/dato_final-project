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

$id        = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$firstname = trim($_POST['firstname'] ?? '');
$lastname  = trim($_POST['lastname'] ?? '');

if (!$id || $firstname === '' || $lastname === '' || strlen($firstname) > 50 || strlen($lastname) > 50) {
    header("Location: dashboard.php?error=" . urlencode("Invalid student details provided."));
    exit;
}

$stmt = $conn->prepare("UPDATE students SET firstname = ?, lastname = ? WHERE id = ?");
$stmt->bind_param("ssi", $firstname, $lastname, $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: dashboard.php?success=" . urlencode("Student details updated successfully."));
    exit;
}

$stmt->close();
$conn->close();
header("Location: dashboard.php?error=" . urlencode("Failed to update student."));
exit;
?>