<?php
include('config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $destination_id = $conn->real_escape_string($_GET['id']);

    // Query to fetch the details of the specific destination
    $query = "SELECT desti_name, desti_description, image_url, city FROM destinations WHERE destination_id = $destination_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
    } else {
        echo "<p>Destination not found.</p>";
        exit;
    }

    // Query to fetch available rooms in the same city
    $city = $destination['city'];
    $rooms_query = "SELECT rooms.room_id, rooms.room_name, rooms.room_description, rooms.price_per_night, hotels.hotel_name 
                    FROM rooms 
                    JOIN hotels ON rooms.hotel_id = hotels.hotel_id 
                    WHERE hotels.location = '$city'";
    $rooms_result = $conn->query($rooms_query);

} else {
    echo "<p>No destination specified.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($destination['desti_name']); ?> - Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($destination['desti_name']); ?></h1>
        <img src="<?php echo htmlspecialchars($destination['image_url']); ?>" alt="<?php echo htmlspecialchars($destination['desti_name']); ?>">
        <p><strong>City:</strong> <?php echo htmlspecialchars($destination['city']); ?></p>
        <p><?php echo htmlspecialchars($destination['desti_description']); ?></p>
        <a href="travel_destination.php" class="back-link">Back to Destinations</a>

        <h2>Available Rooms in <?php echo htmlspecialchars($destination['city']); ?></h2>
        <div class="rooms-list">
            <?php
            if ($rooms_result->num_rows > 0) {
                while ($room = $rooms_result->fetch_assoc()) {
                    echo "<div class='room'>";
                    echo "<h3>" .  htmlspecialchars($room['hotel_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($room['room_description']) . "</p>";
                    echo "<p><strong>Price per Night:</strong> $" . htmlspecialchars($room['price_per_night']) . "</p>";
                    echo '<a href="room_details.php?id=' . htmlspecialchars($room['room_id']) . '" class="details-link">View Details</a>';
                    echo "</div>";
                }
            } else {
                echo "<p>No available rooms in this city.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
