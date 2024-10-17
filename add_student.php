<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $lastname = $_POST['LastName'];
    $firstname = $_POST['FirstName'];
    $address = $_POST['Address'];
    $city = $_POST['City'];

    // Validate form data
    if (empty($lastname) || empty($firstname) || empty($address) || empty($city)) {
        $error = 'All fields are required.';
    } else {
        // Insert new student into database
        $sql = "INSERT INTO students (LastName, FirstName, Address, City) VALUES (:lastname, :firstname, :address, :city)";
        $stmt = $conn->prepare($sql);
        
        // Execute the statement and check for success
        if ($stmt->execute(['lastname' => $lastname, 'firstname' => $firstname, 'address' => $address, 'city' => $city])) {
            $success = 'Student added successfully!';
            header("Location: index.php"); // Redirect to the index page after adding
            exit();
        } else {
            $error = 'Error adding student. Please try again.'; // Error message if insert fails
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Add New Student</h2>

        <?php if (!empty($error)): ?>
            <div class="error fade-in"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="fade-in">
            <div class="form-group">
                <label for="LastName">Last Name</label>
                <input type="text" id="LastName" name="LastName" required>
            </div>

            <div class="form-group">
                <label for="FirstName">First Name</label>
                <input type="text" id="FirstName" name="FirstName" required>
            </div>

            <div class="form-group">
                <label for="Address">Address</label>
                <input type="text" id="Address" name="Address" required>
            </div>

            <div class="form-group">
                <label for="City">City</label>
                <input type="text" id="City" name="City" required>
            </div>

            <input type="submit" value="Add Student" class="button">
            <a href="index.php" class="button">Cancel</a>
        </form>
    </div>
</body>
</html>

