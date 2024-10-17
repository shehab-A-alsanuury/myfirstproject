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
    // Fetch the student record
    $stmt = $conn->prepare("SELECT * FROM students WHERE ID = :id");
    $stmt->execute(['id' => $id]);
    $student = $stmt->fetch();

    if (!$student) {
        throw new Exception("Student not found!");
    }

    // Process the form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lastname = sanitize_input($_POST['LastName']);
        $firstname = sanitize_input($_POST['FirstName']);
        $address = sanitize_input($_POST['Address']);
        $city = sanitize_input($_POST['City']);

        $stmt = $conn->prepare("UPDATE students SET LastName = :lastname, FirstName = :firstname, Address = :address, City = :city WHERE ID = :id");
        $stmt->execute([
            'lastname' => $lastname,
            'firstname' => $firstname,
            'address' => $address,
            'city' => $city,
            'id' => $id
        ]);

        $_SESSION['success_message'] = "Student updated successfully!";
        header("Location: index.php");
        exit;
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="slide-in">Edit Student</h2>
        <?php if (isset($error)): ?>
            <p class="error fade-in"><?php echo $error; ?></p>
        <?php else: ?>
            <form method="POST" class="fade-in">
                <label for="LastName">Last Name:</label>
                <input type="text" id="LastName" name="LastName" value="<?php echo htmlspecialchars($student['LastName']); ?>" required>

                <label for="FirstName">First Name:</label>
                <input type="text" id="FirstName" name="FirstName" value="<?php echo htmlspecialchars($student['FirstName']); ?>" required>

                <label for="Address">Address:</label>
                <input type="text" id="Address" name="Address" value="<?php echo htmlspecialchars($student['Address']); ?>" required>

                <label for="City">City:</label>
                <input type="text" id="City" name="City" value="<?php echo htmlspecialchars($student['City']); ?>" required>

                <input type="submit" value="Update" class="button">
                <a href="index.php" class="button">Cancel</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
