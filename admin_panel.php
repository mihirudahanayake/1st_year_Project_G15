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

// Fetch all hotels
$hotels_stmt = $conn->prepare("SELECT * FROM hotels");
$hotels_stmt->execute();
$hotels = $hotels_stmt->get_result();
$hotels_stmt->close();

// Fetch all users, including user_type and associated hotels if user_type is hotel_admin
$users_stmt = $conn->prepare("
    SELECT users.*, hotels.hotel_name 
    FROM users 
    LEFT JOIN hotels ON users.hotel_id = hotels.hotel_id
    WHERE users.user_type != 'admin'
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
                <th>Actions</th>
            </tr>
            <?php while ($hotel = $hotels->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($hotel['hotel_name']); ?></td>
                <td><?php echo htmlspecialchars($hotel['location']); ?></td>
                <td>
                    <a href="edit_hotel.php?id=<?php echo $hotel['hotel_id']; ?>">Edit</a>
                    <a href="admin_delete_hotel.php?id=<?php echo $hotel['hotel_id']; ?>" onclick="return confirm('Are you sure you want to delete this hotel?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Manage Rooms</h3>
        <table>
            <tr>
                <th>Hotel Name</th>
                <th>Room Name</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
            <?php
            $rooms_query = "
                SELECT rooms.room_id, rooms.room_name, rooms.availability, hotels.hotel_name 
                FROM rooms 
                JOIN hotels ON rooms.hotel_id = hotels.hotel_id";
            $rooms_result = $conn->query($rooms_query);
            while ($room = $rooms_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($room['hotel_name']); ?></td>
                <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                <td><?php echo htmlspecialchars($room['availability']); ?></td>
                <td>
                    <a href="admin_edit_room.php?id=<?php echo $room['room_id']; ?>">Edit</a>
                    <a href="admin_delete_room.php?id=<?php echo $room['room_id']; ?>" onclick="return confirm('Are you sure you want to delete this room?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h3>Manage Users</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th> <!-- New column for user type -->
                <th>Hotel Name</th> <!-- New column for hotel name -->
                <th>Actions</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['user_type']); ?></td> <!-- Display user type -->
                <td>
                    <?php echo $user['user_type'] === 'hotel_admin' ? htmlspecialchars($user['hotel_name']) : 'N/A'; ?>
                </td>
                <td>
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
