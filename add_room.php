<?php
session_start();
include('config.php');

// Check if the admin is logged in
if ($_SESSION['user_type'] != 'hotel_admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel_id = $_SESSION['hotel_id']; // Assuming admin's ID is linked to hotel ID
    $room_number = $_POST['room_number'];
    $room_name = $_POST['room_name'];
    $capacity_adults = $_POST['max_adults'];
    $capacity_children = $_POST['max_children'];
    $facilities = $_POST['facilities'];
    $price_per_night = $_POST['price_per_night'];

    // Insert the room into the database
    $stmt = $conn->prepare("INSERT INTO rooms (hotel_id, room_name, room_number, max_adults, max_children, facilities, price_per_night) VALUES (?, ?, FALSE, ?, ?, ?, ?)");
    $stmt->bind_param("isiiis", $hotel_id, $room_number, $capacity_adults, $capacity_children, $facilities, $price_per_night);

    if ($stmt->execute()) {
        echo "Room added successfully!";
        header("Location: hotel_dashboard.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Add Room</h2>
        <form action="add_room.php" method="POST">
            <label for="room_number">Room Number</label>
            <input type="text" id="room_number" name="room_number" required>

            <label for="room_name">Room Name</label>
            <input type="text" id="room_name" name="room_name" required>

            <label for="max_adults">Capacity (Adults)</label>
            <input type="number" id="max_adults" name="max_adults" required>

            <label for="max_children">Capacity (Children)</label>
            <input type="number" id="max_children" name="max_children" required>

            <label for="facilities">Facilities</label>
            <textarea id="facilities" name="facilities" required></textarea>

            <label for="price_per_night">Price per Night</label>
            <input type="number" id="price_per_night" name="price_per_night" step="0.01" required>

            <button type="submit">Add Room</button>
        </form>
    </div>
</body>
</html> -->
