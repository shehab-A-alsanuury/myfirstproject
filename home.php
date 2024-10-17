<?php
session_start();
// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Fetch student records from the database
$stmt = $conn->prepare("SELECT ID, LastName, FirstName, Address, City FROM students ORDER BY LastName");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Student Management System</h1>
            <nav>
                <a href="logout.php" class="button">Logout</a>
            </nav>
        </header>

        <main>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

            <div class="welcome-message fade-in">
                Manage your student records easily with our system.
            </div>

            <section class="student-list fade-in">
                <h3>Student Records</h3>
                <?php if (empty($students)): ?>
                    <p>No student records found.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $index => $student): ?>
                                <tr style="--animation-order: <?php echo $index; ?>;">
                                    <td><?php echo htmlspecialchars($student['ID']); ?></td>
                                    <td><?php echo htmlspecialchars($student['LastName']); ?></td>
                                    <td><?php echo htmlspecialchars($student['FirstName']); ?></td>
                                    <td><?php echo htmlspecialchars($student['Address']); ?></td>
                                    <td><?php echo htmlspecialchars($student['City']); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $student['ID']; ?>" class="button">Edit</a>
                                        <a href="delete.php?id=<?php echo $student['ID']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>

            <a href="add_student.php" class="button">Add New Student</a>
        </main>
    </div>
</body>
</html>
