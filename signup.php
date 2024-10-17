<?php
session_start();
include('config.php'); 

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = 'Username already exists. Please choose another.';
        } else {
            // Handle file upload
            $profile_picture = '';
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/';
                $file_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
                $target_file = $upload_dir . $file_name;

                // Check if image file is an actual image or fake image
                $check = getimagesize($_FILES['profile_picture']['tmp_name']);
                if ($check !== false) {
                    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                        $profile_picture = $target_file;
                    } else {
                        $error = 'Sorry, there was an error uploading your file.';
                    }
                } else {
                    $error = 'File is not an image.';
                }
            }

            if (empty($error)) {
               
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, password, profile_picture) VALUES (:username, :password, :profile_picture)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['username' => $username, 'password' => $hashed_password, 'profile_picture' => $profile_picture]);

                $success = 'Account created successfully! You can now log in.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>

      
        <div id="successModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeSuccessModal">&times;</span>
                <p><?php if (!empty($success)) { echo htmlspecialchars($success); } ?></p>
            </div>
        </div>

       
        <div id="errorModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeErrorModal">&times;</span>
                <p><?php if (!empty($error)) { echo htmlspecialchars($error); } ?></p>
            </div>
        </div>

        <form action="signup.php" method="POST" enctype="multipart/form-data" class="fade-in">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            </div>

            <input type="submit" value="Sign Up" class="button">
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <script>
        window.onload = function() {
            <?php if (!empty($success)) : ?>
                document.getElementById('successModal').style.display = 'block';
            <?php elseif (!empty($error)) : ?>
                document.getElementById('errorModal').style.display = 'block';
            <?php endif; ?>
        }

        document.getElementById('closeSuccessModal').onclick = function() {
            document.getElementById('successModal').style.display = 'none';
        }
        document.getElementById('closeErrorModal').onclick = function() {
            document.getElementById('errorModal').style.display = 'none';
        }
    </script>
</body>
</html>