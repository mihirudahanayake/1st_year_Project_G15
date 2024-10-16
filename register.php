<?php

include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already taken.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Set default user type
    $user_type = 'user';

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $user_type);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("Location: login.html");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="signup-container"></div>
        <div class="signup-box">
            <h2>Sign Up</h2>
            <form action="register.php" method="POST">
                <label for="username">Username</label>
                <div class="textbox">
                    <input type="text" id="username" name="username" required>
                </div>

                <label for="email">Email</label>
                <div class="textbox">
                    <input type="email" id="email" name="email" required>
                </div>

                <label for="password">Password</label>
                <div class="textbox">
                    <input type="password" id="password" name="password" required>
                </div>

                <label for="confirm_password">Confirm Password</label>
                <div class="textbox">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            
                <button type="submit" class="btn">Sign Up</button>
            </form>
            <div class="signup-link">
                <p>Already have an account? <a href="login.html">Login here</a>.</p>
            </div>
        </div>   
</body>
</html>
