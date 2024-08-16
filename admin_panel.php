<?php
session_start();
include('config.php');

// Check if the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Handle delete user action
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_panel.php");
    exit();
}

// Handle update user type action
if (isset($_POST['update_user_type'])) {
    $user_id = $_POST['user_id'];
    $new_user_type = $_POST['user_type'];

    $stmt = $conn->prepare("UPDATE users SET user_type = ? WHERE user_id = ?");
    $stmt->bind_param("si", $new_user_type, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_panel.php");
    exit();
}

// Fetch all hotels along with the hotel admin's name
$hotels_stmt = $conn->prepare("
    SELECT hotels.*, users.username AS admin_name
    FROM hotels
    LEFT JOIN users ON hotels.hotel_id = users.hotel_id
    WHERE users.user_type = 'hotel_admin'
");
$hotels_stmt->execute();
$hotels = $hotels_stmt->get_result();
$hotels_stmt->close();

// Fetch all users excluding admin users
$users_stmt = $conn->prepare("
    SELECT * FROM users WHERE user_type != 'admin'
");
$users_stmt->execute();
$users = $users_stmt->get_result();
$users_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>Admin Panel</h2>

        <h3>Manage Hotels</h3>
        <table>
            <tr>
                <th>Hotel Name</th>
                <th>Location</th>
                <th>Hotel Admin</th>
                <th>Actions</th>
            </tr>
            <?php while ($hotel = $hotels->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($hotel['hotel_name']); ?></td>
                <td><?php echo htmlspecialchars($hotel['location']); ?></td>
                <td><?php echo htmlspecialchars($hotel['admin_name']); ?></td>
                <td>
                    <a href="admin_hotel_details.php?id=<?php echo $hotel['hotel_id']; ?>">View Details</a>
                    <a href="edit_hotel.php?id=<?php echo $hotel['hotel_id']; ?>">Edit</a>
                    <a href="admin_delete_hotel.php?id=<?php echo $hotel['hotel_id']; ?>" onclick="return confirm('Are you sure you want to delete this hotel?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Manage Users</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                <td>
                    <a href="admin_edit_user.php?id=<?php echo $user['user_id']; ?>">Edit</a>
                    <?php if ($user['user_type'] === 'hotel_admin'): ?>
                        <a href="view_hotel_details.php?id=<?php echo $user['hotel_id']; ?>">View Hotel Details</a>
                    <?php else: ?>
                        <a href="admin_view_user_bookings.php?id=<?php echo $user['user_id']; ?>">View Booking Details</a>
                    <?php endif; ?>
                    <a href="admin_delete_user.php?id=<?php echo $user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>

            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
