<?php
include('config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $destination_id = $conn->real_escape_string($_GET['id']);

    // Query to fetch the details of the specific destination
    $query = "SELECT desti_name, desti_description FROM destinations WHERE destination_id = $destination_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
    } else {
        echo "<p>Destination not found.</p>";
        exit;
    }

    // Query to fetch images associated with this destination from destination_images table
    $image_query = "SELECT image_url FROM destination_images WHERE destination_id = $destination_id";
    $image_result = $conn->query($image_query);

    // Query to fetch available rooms associated with this destination
    $rooms_query = "
        SELECT rooms.room_id, rooms.room_name, rooms.facilities, rooms.price_per_night, hotels.hotel_name 
        FROM rooms
        JOIN hotels ON rooms.hotel_id = hotels.hotel_id
        JOIN hotel_destinations ON hotels.hotel_id = hotel_destinations.hotel_id
        WHERE hotel_destinations.destination_id = $destination_id
    ";
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
        <p><?php echo htmlspecialchars($destination['desti_description']); ?></p>

        <!-- Display images -->
        <h2>Images of <?php echo htmlspecialchars($destination['desti_name']); ?></h2>
        <div class="image-gallery">
            <?php
            if ($image_result->num_rows > 0) {
                while ($image = $image_result->fetch_assoc()) {
                    echo "<img src='" . htmlspecialchars($image['image_url']) . "' alt='Image of " . htmlspecialchars($destination['desti_name']) . "'>";
                }
            } else {
                echo "<p>No images available for this destination.</p>";
            }
            ?>
        </div>

        <a href="travel_destination.php" class="back-link">Back to Destinations</a>

        <h2>Available Hotel Rooms near <?php echo htmlspecialchars($destination['desti_name']); ?></h2>
        <div class="rooms-list">
            <?php
            if ($rooms_result->num_rows > 0) {
                while ($room = $rooms_result->fetch_assoc()) {
                    echo "<div class='room'>";
                    echo "<h3>" .  htmlspecialchars($room['hotel_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($room['facilities']) . "</p>";
                    echo "<p><strong>Price per Night:</strong> $" . htmlspecialchars($room['price_per_night']) . "</p>";
                    echo '<a href="room_details.php?id=' . htmlspecialchars($room['room_id']) . '" class="details-link">View Details</a>';
                    echo "</div>";
                }
            } else {
                echo "<p>No available rooms near this destination.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
