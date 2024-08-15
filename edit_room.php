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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_name = $_POST['room_name'];
    $room_description = $_POST['room_description'];
    $price_per_night = $_POST['price_per_night'];
    $max_adults = $_POST['max_adults'];
    $max_children = $_POST['max_children'];
    $availability = $_POST['availability'];

    $stmt = $conn->prepare("UPDATE rooms SET room_name = ?, room_description = ?, price_per_night = ?, max_adults = ?, max_children = ?, availability = ? WHERE room_id = ?");
    $stmt->bind_param("ssdiisi", $room_name, $room_description, $price_per_night, $max_adults, $max_children, $availability, $room_id);
    $stmt->execute();
    $stmt->close();

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
    <form action="edit_room.php?room_id=<?php echo $room_id; ?>" method="POST">
        <label for="room_number">Room Number</label>
        <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>

        <label for="room_name">Room Name</label>
        <input type="text" id="room_name" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" required>

        <label for="facilities">Room Description</label>
        <textarea id="facilities" name="facilities" required><?php echo htmlspecialchars($room['facilities']); ?></textarea>

        <label for="price_per_night">Price per Night</label>
        <input type="number" id="price_per_night" name="price_per_night" value="<?php echo htmlspecialchars($room['price_per_night']); ?>" required>

        <label for="max_adults">Max Adults</label>
        <input type="number" id="max_adults" name="max_adults" value="<?php echo htmlspecialchars($room['max_adults']); ?>" required>

        <label for="max_children">Max Children</label>
        <input type="number" id="max_children" name="max_children" value="<?php echo htmlspecialchars($room['max_children']); ?>" required>

        <label for="availability">Availability</label>
        <input type="text" id="availability" name="availability" value="<?php echo htmlspecialchars($room['availability']); ?>" required>

        <button type="submit">Update Room</button>
    </form>
</body>
</html>
