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
    $availability = $_POST['availability'];
    $capacity_adults = $_POST['max_adults'];
    $capacity_children = $_POST['max_children'];
    $facilities = $_POST['facilities'];
    $price_per_night = $_POST['price_per_night'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO rooms (hotel_id, room_number, room_name, max_adults, max_children, facilities, price_per_night, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind the parameters to the statement
    $stmt->bind_param("issiisss", $hotel_id, $room_number, $room_name, $capacity_adults, $capacity_children, $facilities, $price_per_night, $availability);

    // Execute the statement and handle errors
    if ($stmt->execute()) {
        echo "Room added successfully!";
        header("Location: hotel_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
