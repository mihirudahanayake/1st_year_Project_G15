<?php

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

        // Booking form processing logic
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'];
            $room_id = $_POST['room_id'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // Insert the booking details into the bookings table
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, start_date, end_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $user_id, $room_id, $start_date, $end_date);
            $stmt->execute();
            
            // Get the ID of the newly created booking
            $booking_id = $stmt->insert_id;
            
            // Fetch room details (including room number and hotel_id) for the notification
            $stmt_room = $conn->prepare("SELECT room_number, hotel_id FROM rooms WHERE room_id = ?");
            $stmt_room->bind_param("i", $room_id);
            $stmt_room->execute();
            $result = $stmt_room->get_result();
            $room = $result->fetch_assoc();
            
            // Now insert the notification with hotel_id
            $hotel_id = $room['hotel_id'];
            $message = "A new booking has been made for room " . $room['room_number'];
            $stmt_notif = $conn->prepare("INSERT INTO notifications (user_id, room_id, booking_id, hotel_id, message, status) VALUES (?, ?, ?, ?, ?, 'unread')");
            $stmt_notif->bind_param("iiiis", $user_id, $room_id, $booking_id, $hotel_id, $message);
            $stmt_notif->execute();

            // Close statements
            $stmt->close();
            $stmt_room->close();
            $stmt_notif->close();
            
            // Redirect or show success message
            $room_id = $conn->real_escape_string($_POST['room_id']);
            header("Location: room_details.php?id=$room_id");
        }
    } else {
        $room_id = $conn->real_escape_string($_POST['room_id']);
        header("Location: room_details.php?id=$room_id");
        
    }
}

// After booking success
$hotel_id_query = $conn->prepare("SELECT hotel_id FROM rooms WHERE room_id = ?");
$hotel_id_query->bind_param("i", $room_id);
$hotel_id_query->execute();
$hotel_id_query->bind_result($hotel_id);
$hotel_id_query->fetch();
$hotel_id_query->close();

$conn->close();
?>
