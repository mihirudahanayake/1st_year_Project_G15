<?php

include('config.php'); // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Fetch user bookings
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT bookings.booking_id, bookings.start_date, bookings.end_date, rooms.room_name, hotels.hotel_name 
                        FROM bookings 
                        JOIN rooms ON bookings.room_id = rooms.room_id 
                        JOIN hotels ON rooms.hotel_id = hotels.hotel_id 
                        WHERE bookings.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="profile-container">
        <h2>Your Bookings</h2>
        <?php if ($bookings_result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Hotel Name</th>
                    <th>Room Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
                <?php while($booking = $bookings_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['hotel_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                    
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>You have no bookings.</p>
        <?php endif; ?>

        <a href="profile.php" class="back-link">Back to Profile</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
