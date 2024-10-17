<?php
session_start();
include('config.php');

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="slide-in">Login to Student Management System</h2>

        <?php if (!empty($error)): ?>
            <div class="error fade-in"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="fade-in">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required aria-required="true">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required aria-required="true">
            </div>

            <input type="submit" value="Login" class="button">
        </form>

        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </div>

    <script>
     
        document.querySelector('form').addEventListener('submit', function(e) {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            
            if (username.trim() === '' || password.trim() === '') {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    </script>
</body>
</html>