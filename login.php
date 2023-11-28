<?php
session_start();
require 'db.php';
require 'functions.php';
// $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = getUser($username);
    echo $user;
    if ($user) {
        // header('Location: signup.php');
        if(password_verify($password, $user['password'])){
            $_SESSION['username'] = $username;
            header('Location: game.php');
            exit();
        }
        
    } else {
        $error = 'Invalid username or password';
        
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>
</html>
