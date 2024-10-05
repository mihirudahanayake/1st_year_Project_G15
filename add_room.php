<?php

include('config.php');

// Check if the user is logged in and is a hotel admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'hotel_admin') {
    header("Location: login.html");
    exit();
}

// Fetch hotel_id from session
$hotel_id = $_SESSION['hotel_id'];

if (isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $room_name = $_POST['room_name'];
    $facilities = $_POST['facilities'];
    $price_per_night = $_POST['price_per_night'];
    $max_adults = $_POST['max_adults'];
    $max_children = $_POST['max_children'];
    $availability = $_POST['availability'];

    // Insert room details into the database
    $stmt = $conn->prepare("INSERT INTO rooms (hotel_id, room_number, room_name, facilities, price_per_night, max_adults, max_children, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissiiis", $hotel_id, $room_number, $room_name, $facilities, $price_per_night, $max_adults, $max_children, $availability);
    $stmt->execute();
    $room_id = $stmt->insert_id; // Get the inserted room ID
    $stmt->close();

    // Handle file uploads
    $upload_dir = 'uploads/rooms/'; // Make sure this directory exists and is writable

    $image_count = count($_FILES['room_images']['name']);
    if ($image_count > 5) {
        $image_count = 5; // Limit to 5 images
    }

    // Count existing images for the room
    $stmt = $conn->prepare("SELECT COUNT(*) AS image_count FROM room_images WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $stmt->bind_result($existing_image_count);
    $stmt->fetch();
    $stmt->close();

    // Start numbering new images after existing images
    $image_index = $existing_image_count + 1;

    for ($i = 0; $i < $image_count; $i++) {
        $image_name = $_FILES['room_images']['name'][$i];
        $image_tmp_name = $_FILES['room_images']['tmp_name'][$i];
        $image_size = $_FILES['room_images']['size'][$i];
        $image_error = $_FILES['room_images']['error'][$i];

        if ($image_error === UPLOAD_ERR_OK) {
            // Generate a file name following the format room_$room_number_imageX.jpg
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_new_name = 'room_' . $room_number . '_image' . $image_index . '.' . $image_ext;
            $image_index++; // Increment the image index

            // Move the uploaded file to the destination directory
            move_uploaded_file($image_tmp_name, $upload_dir . $image_new_name);

            // Save the full image path to the database
            $full_image_path = $upload_dir . $image_new_name; // Include the directory path
            $stmt = $conn->prepare("INSERT INTO room_images (room_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $room_id, $full_image_path);
            $stmt->execute();
        }
    }

    $_SESSION['success_message'] = "Room added successfully.";
    header("Location: hotel_dashboard.php");
    exit();
}
?>
