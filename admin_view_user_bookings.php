<?php

include('config.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Check if user_id is provided
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user bookings
    $stmt = $conn->prepare("SELECT bookings.booking_id, bookings.room_id, bookings.start_date, bookings.end_date, rooms.room_name, hotels.hotel_name 
                            FROM bookings 
                            JOIN rooms ON bookings.room_id = rooms.room_id 
                            JOIN hotels ON rooms.hotel_id = hotels.hotel_id 
                            WHERE bookings.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    echo "User ID not provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Bookings</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>User Bookings</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Room Name</th>
                    <th>Hotel Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
                <?php while ($booking = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['hotel_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No bookings found for this user.</p>
        <?php endif; ?>
        <button><a href="admin_panel.php">Back to Admin Panel</a></button>
    </div>
</body>
</html>
