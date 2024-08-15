<?php
session_start(); // Start the session to access session variables
include('config.php');

// Check if the form was submitted
if (isset($_POST['book_now'])) {
    // Ensure user is logged in and session is set
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Assume user ID is stored in session
        $room_id = $conn->real_escape_string($_POST['room_id']);
        $start_date = $conn->real_escape_string($_POST['start_date']);
        $end_date = $conn->real_escape_string($_POST['end_date']);

        // Insert booking into the database
        $query = "INSERT INTO bookings (user_id, room_id, start_date, end_date, booking_status)
                  VALUES ($user_id, $room_id, '$start_date', '$end_date', 'confirmed')";

        if ($conn->query($query) === TRUE) {
            echo "<p>Booking confirmed!</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>You must be logged in to book a room.</p>";
    }
}

// After successfully inserting the booking
$hotel_id_query = $conn->prepare("SELECT hotel_id FROM rooms WHERE room_id = ?");
$hotel_id_query->bind_param("i", $room_id);
$hotel_id_query->execute();
$hotel_id_query->bind_result($hotel_id);
$hotel_id_query->fetch();
$hotel_id_query->close();

$notification_message = "Room " . $room_id . " has been booked from " . $start_date . " to " . $end_date . ".";

$notification_stmt = $conn->prepare("INSERT INTO notifications (hotel_id, room_id, message) VALUES (?, ?, ?)");
$notification_stmt->bind_param("iis", $hotel_id, $room_id, $notification_message);
$notification_stmt->execute();
$notification_stmt->close();


$conn->close();
?>
