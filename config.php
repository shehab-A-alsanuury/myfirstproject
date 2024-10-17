<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "students";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to sanitize user inputs
function sanitize_input($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}
?>