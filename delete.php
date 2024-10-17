<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


try {
    $stmt = $conn->prepare("DELETE FROM students WHERE ID = :id");

    if ($stmt->execute(['id' => $id])) {
        $_SESSION['success_message'] = "Student deleted successfully!";
    } else {
        throw new Exception("An error occurred while deleting the student.");
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
}


header("Location: index.php");
exit;
?>
