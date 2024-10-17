<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


try {
    $stmt = $conn->prepare("SELECT username, profile_picture FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Fetch student data from the database
try {
    $stmt = $conn->prepare("SELECT ID, LastName, FirstName, Address, City FROM students ORDER BY LastName");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .user-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
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
            <div class="user-profile">
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <img src="default_profile.png" alt="Default Profile Picture">
                <?php endif; ?>
                <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            </div>

            <section class="student-list">
                <h3>Student Records</h3>
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
                                    <a href="#" class="button" onclick="confirmDelete('delete.php?id=<?php echo $student['ID']; ?>'); return false;">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

         
        </main>
    </div>

    
    <div id="confirmModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <p>Are you sure you want to delete this student?</p>
            <button id="confirmDelete" class="button">Yes, delete</button>
            <button id="cancelDelete" class="button">No, cancel</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmModal');
            const confirmDeleteButton = document.getElementById('confirmDelete');
            const cancelDeleteButton = document.getElementById('cancelDelete');
            const closeModal = document.getElementById('closeModal');
            let deleteUrl;

            
            window.confirmDelete = function(url) {
                deleteUrl = url; 
                modal.style.display = 'block'; 
            };

            
            closeModal.onclick = function() {
                modal.style.display = 'none';
            };
            cancelDeleteButton.onclick = function() {
                modal.style.display = 'none';
            };

            // Confirm delete action
            confirmDeleteButton.onclick = function() {
                window.location.href = deleteUrl; 
            };
        });

  
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>
</html>