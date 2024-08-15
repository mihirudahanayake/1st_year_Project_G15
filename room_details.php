<?php
include('config.php');

// Get the room ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $room_id = $conn->real_escape_string($_GET['id']);

    // Fetch room details
    $query = "SELECT rooms.*, hotels.hotel_name AS hotel_name, hotels.location
              FROM rooms
              JOIN hotels ON rooms.hotel_id = hotels.hotel_id
              WHERE rooms.room_id = $room_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "<p>Room not found.</p>";
        exit;
    }

    // Fetch all destinations associated with the room
    $destQuery = "SELECT destinations.desti_name
                  FROM hotel_destinations
                  JOIN destinations ON hotel_destinations.destination_id = destinations.destination_id
                  WHERE hotel_destinations.hotel_id = (SELECT hotel_id FROM rooms WHERE room_id = $room_id)";
    $destResult = $conn->query($destQuery);

    $destinations = [];
    if ($destResult->num_rows > 0) {
        while ($row = $destResult->fetch_assoc()) {
            $destinations[] = $row['desti_name'];
        }
    }

} else {
    echo "<p>No room specified.</p>";
    exit;
}

// Handle availability check form submission
if (isset($_POST['check_availability'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate the date range
    if ($start_date && $end_date && $start_date <= $end_date) {
        // Query to check availability
        $query = "SELECT * FROM bookings
                  WHERE room_id = $room_id
                    AND (start_date <= '$end_date' AND end_date >= '$start_date')";
        $result = $conn->query($query);

        $is_available = ($result->num_rows == 0);
    } else {
        echo "<p>Invalid date range.</p>";
        $is_available = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Room Details</h1>
        <h2><?php echo $room['room_name']; ?></h2>
        <p><strong>Hotel:</strong> <?php echo $room['hotel_name']; ?></p>
        <p><strong>Location:</strong> <?php echo $room['location']; ?></p>
        <p><strong>Description:</strong> <?php echo $room['room_description']; ?></p>
        <p><strong>Price per Night:</strong> $<?php echo $room['price_per_night']; ?></p>
        <p><strong>Max Adults:</strong> <?php echo $room['max_adults']; ?></p>
        <p><strong>Max Children:</strong> <?php echo $room['max_children']; ?></p>

        <h2>Destinations</h2>
        <?php if (!empty($destinations)): ?>
            <p><?php echo implode(', ', $destinations); ?></p>
        <?php else: ?>
            <p>No destinations found for this room.</p>
        <?php endif; ?>

        <h2>Check Availability</h2>
        <form action="room_details.php?id=<?php echo $room_id; ?>" method="POST">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" required>

            <button type="submit" name="check_availability">Check Availability</button>
        </form>

        <?php if (isset($is_available)): ?>
            <h2>Availability Status</h2>
            <?php if ($is_available): ?>
                <p>The room is available for the selected dates.</p>
                <form action="book_room.php" method="POST">
                    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                    <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                    <button type="submit" name="book_now">Book Now</button>
                </form>
            <?php else: ?>
                <p>Sorry, the room is not available for the selected dates.</p>
            <?php endif; ?>
        <?php endif; ?>

        <a href="index.php">Back to Home</a>
    </div>
</body>
</html>
