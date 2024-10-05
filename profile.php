<?php

include('config.php'); // Include your database connection file

// Check if the user is logged in
// if (!isset($_SESSION['id'])) {
//     header("Location: login.html");
//     exit;
// }

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, user_type FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <h2>Your Profile</h2>
        <form action="update_profile.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="password">New Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password">

            <button type="submit">Update Profile</button>
        </form>

        <?php if ($user['user_type'] === 'user'): ?>
        <a href="view_booking.php" class="view-bookings-button">View Bookings</a>
        <?php endif; ?>

        <button onclick="location.href='index.html'">Back to Home</button>
        <button onclick="location.href='logout.php'">Log out</button>
    </div>
</body>
</html>
