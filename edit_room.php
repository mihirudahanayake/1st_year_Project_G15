<?php
session_start();
include('config.php');

// Check if the user is logged in and is a hotel admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'hotel_admin' ) {
    header("Location: login.html");
    exit();
}

// Fetch room details
$room_id = $_GET['room_id'];
$stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle room update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_id = $_POST['room_id']; // Assuming you're passing the room_id to identify the room being updated
    $hotel_id = $_SESSION['hotel_id']; // Assuming the admin's ID is linked to hotel ID
    $room_number = $_POST['room_number'];
    $room_name = $_POST['room_name'];
    $availability = $_POST['availability'];
    $capacity_adults = $_POST['max_adults'];
    $capacity_children = $_POST['max_children'];
    $facilities = $_POST['facilities'];
    $price_per_night = $_POST['price_per_night'];

    // Prepare the SQL statement for updating the room details
    $stmt = $conn->prepare("
        UPDATE rooms 
        SET 
            room_number = ?, 
            room_name = ?, 
            max_adults = ?, 
            max_children = ?, 
            facilities = ?, 
            price_per_night = ?, 
            availability = ?
        WHERE room_id = ? AND hotel_id = ?
    ");

    // Bind the parameters to the statement
    $stmt->bind_param("ssiisssii", $room_number, $room_name, $capacity_adults, $capacity_children, $facilities, $price_per_night, $availability, $room_id, $hotel_id);
    $stmt->execute();
    $stmt->close();

    echo "Room updated successfully!";
    header("Location: hotel_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
</head>
<body>
    <h2>Edit Room</h2>
    <form method="POST" action="edit_room.php">
    <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
    <label for="room_number">Room Number:</label>
    <input type="text" name="room_number" value="<?php echo $room['room_number']; ?>">

    <label for="room_name">Room Name:</label>
    <input type="text" name="room_name" value="<?php echo $room['room_name']; ?>">

    <label for="max_adults">Maximum Adults:</label>
    <input type="number" name="max_adults" value="<?php echo $room['max_adults']; ?>">

    <label for="max_children">Maximum Children:</label>
    <input type="number" name="max_children" value="<?php echo $room['max_children']; ?>">

    <label for="facilities">Facilities:</label>
    <textarea name="facilities"><?php echo $room['facilities']; ?></textarea>

    <label for="price_per_night">Price Per Night:</label>
    <input type="text" name="price_per_night" value="<?php echo $room['price_per_night']; ?>">

    <label for="availability">Availability:</label>
    <select name="availability">
        <option value="available" <?php if ($room['availability'] == 'available') echo 'selected'; ?>>Available</option>
        <option value="unavailable" <?php if ($room['availability'] == 'unavailable') echo 'selected'; ?>>Unavailable</option>
    </select>

    <button type="submit">Update Room</button>
</form>

</body>
</html>
