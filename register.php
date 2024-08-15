<?php
session_start();
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];

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
