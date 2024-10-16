<?php

include('config.php');

// Check if the user is logged in and is a hotel admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'hotel_admin') {
    header("Location: login.html");
    exit();
}

// Fetch hotel_id from session
$hotel_id = $_SESSION['hotel_id'];

// Fetch the hotel details for the current user
$hotel_stmt = $conn->prepare("SELECT hotel_id FROM hotels WHERE hotel_id = ? AND user_id = ?");
$hotel_stmt->bind_param("ii", $hotel_id, $_SESSION['user_id']);
$hotel_stmt->execute();
$hotel_result = $hotel_stmt->get_result();
$hotel_stmt->close();

if ($hotel_result->num_rows === 0) {
    // If the hotel does not belong to the logged-in user, redirect to an error page
    $_SESSION['error_message'] = "Add your hotel.";
    header("Location: add_hotel.php"); // or another appropriate page
    exit();
}

// Initialize variables
$cities = [];
$destinations = [];
$message = "";

// Fetch cities from the cities table
$cities_stmt = $conn->prepare("SELECT * FROM cities");
$cities_stmt->execute();
$cities_result = $cities_stmt->get_result();
while ($city = $cities_result->fetch_assoc()) {
    $cities[] = $city;
}
$cities_stmt->close();

// Fetch destinations for the selected city
if (isset($_POST['city'])) {
    $city = $_POST['city'];

    $destinations_stmt = $conn->prepare("SELECT * FROM destinations WHERE city = ?");
    $destinations_stmt->bind_param("s", $city);
    $destinations_stmt->execute();
    $destinations_result = $destinations_stmt->get_result();
    while ($destination = $destinations_result->fetch_assoc()) {
        $destinations[] = $destination;
    }
    $destinations_stmt->close();
}

// Handle adding a new city
if (isset($_POST['add_city'])) {
    $new_city = $_POST['new_city'];

    $check_city_stmt = $conn->prepare("SELECT * FROM cities WHERE city_name = ?");
    $check_city_stmt->bind_param("s", $new_city);
    $check_city_stmt->execute();
    $check_city_result = $check_city_stmt->get_result();
    $check_city_stmt->close();

    if ($check_city_result->num_rows > 0) {
        $message = "City already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO cities (city_name) VALUES (?)");
        $stmt->bind_param("s", $new_city);
        $stmt->execute();
        $stmt->close();
        $message = "City added successfully.";
        
        // Refresh cities list
        $cities_stmt = $conn->prepare("SELECT * FROM cities");
        $cities_stmt->execute();
        $cities_result = $cities_stmt->get_result();
        $cities = $cities_result->fetch_all(MYSQLI_ASSOC);
        $cities_stmt->close();
    }
}

// Handle adding a new destination
if (isset($_POST['add_destination'])) {
    $desti_name = $_POST['desti_name'];
    $desti_description = $_POST['desti_description'];
    $city = $_POST['city'];

    $stmt = $conn->prepare("INSERT INTO destinations (desti_name, desti_description, city) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $desti_name, $desti_description, $city);
    $stmt->execute();
    $stmt->close();
    $message = "Destination added successfully.";
}

// Handle assigning destination to the hotel
if (isset($_POST['assign_destination'])) {
    $destination_id = $_POST['destination_id'];

    // Check if the destination is already assigned to this hotel
    $check_stmt = $conn->prepare("SELECT * FROM hotel_destinations WHERE hotel_id = ? AND destination_id = ?");
    $check_stmt->bind_param("ii", $hotel_id, $destination_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check_stmt->close();

    if ($result->num_rows > 0) {
        $message = "The destination is already assigned to your hotel.";
    } else {
        $stmt = $conn->prepare("INSERT INTO hotel_destinations (hotel_id, destination_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $hotel_id, $destination_id);
        $stmt->execute();
        $stmt->close();
        $message = "Destination successfully assigned to your hotel.";
    }
}

// Fetch already assigned destinations
$assigned_destinations_stmt = $conn->prepare("
    SELECT d.desti_name, d.desti_description, d.city 
    FROM hotel_destinations hd 
    JOIN destinations d ON hd.destination_id = d.destination_id 
    WHERE hd.hotel_id = ?
");
$assigned_destinations_stmt->bind_param("i", $hotel_id);
$assigned_destinations_stmt->execute();
$assigned_destinations_result = $assigned_destinations_stmt->get_result();
$assigned_destinations = $assigned_destinations_result->fetch_all(MYSQLI_ASSOC);
$assigned_destinations_stmt->close();

// Fetch existing images for the hotel
$hotel_images_stmt = $conn->prepare("SELECT * FROM hotel_images WHERE hotel_id = ?");
$hotel_images_stmt->bind_param("i", $hotel_id);
$hotel_images_stmt->execute();
$hotel_images_result = $hotel_images_stmt->get_result();
$hotel_images = $hotel_images_result->fetch_all(MYSQLI_ASSOC);
$hotel_images_stmt->close();




// Handle image upload
if (isset($_POST['upload_image'])) {
    // File upload path
    $target_dir = "uploads/hotel_images/";

    // Check if the directory exists, create if it doesn't
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["hotel_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["hotel_image"]["tmp_name"]);
    if ($check !== false) {
        // Check for upload errors
        if ($_FILES["hotel_image"]["error"] !== UPLOAD_ERR_OK) {
            $message = "File upload error. Code: " . $_FILES["hotel_image"]["error"];
            return; // Exit the script if there’s an upload error
        }

        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $message = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
            return; // Exit the script if the file type is not allowed
        }

        // Avoid overwriting existing files
        $fileName = pathinfo($target_file, PATHINFO_FILENAME);
        $extension = pathinfo($target_file, PATHINFO_EXTENSION);
        $counter = 1;

        while (file_exists($target_file)) {
            $target_file = $target_dir . $fileName . '_' . $counter . '.' . $extension;
            $counter++;
        }

        // Move file to target directory
        if (move_uploaded_file($_FILES["hotel_image"]["tmp_name"], $target_file)) {
            // Insert file path into the database
            $stmt = $conn->prepare("INSERT INTO hotel_images (hotel_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $hotel_id, $target_file);
            $stmt->execute();
            $stmt->close();
            header("Location: hotel_dashboard.php?hotel_id=$hotel_id");
        } else {
            $message = "Error uploading image. Please check folder permissions.";
            error_log("Error moving uploaded file: " . print_r($_FILES["hotel_image"], true)); // Log details for debugging
        }
    } else {
        $message = "File is not an image.";
    }
    
}

// Handle image deletion
if (isset($_POST['delete_image'])) {
    $image_id = $_POST['image_id'];

    // Fetch the image path from the database
    $image_stmt = $conn->prepare("SELECT image_path FROM hotel_images WHERE image_id = ? AND hotel_id = ?");
    $image_stmt->bind_param("ii", $image_id, $hotel_id);
    $image_stmt->execute();
    $image_result = $image_stmt->get_result();
    $image_stmt->close();

    if ($image_result->num_rows > 0) {
        $image = $image_result->fetch_assoc();
        $image_path = $image['image_path'];

        // Delete the image file from the server
        if (unlink($image_path)) {
            // Delete the record from the database
            $delete_stmt = $conn->prepare("DELETE FROM hotel_images WHERE image_id = ? AND hotel_id = ?");
            $delete_stmt->bind_param("ii", $image_id, $hotel_id);
            $delete_stmt->execute();
            $delete_stmt->close();
            header("Location: hotel_dashboard.php?hotel_id=$hotel_id");
        } else {
            $message = "Error deleting image file.";
        }
    } else {
        $message = "Image not found.";
    }
    
}



?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Hotel Dashboard</h2>

        <button onclick="location.href='profile.php'">Profile</button>

        <h3>Manage Hotel Images</h3>

<!-- Form to upload hotel images -->
<form action="hotel_dashboard.php" method="POST" enctype="multipart/form-data">
    <label for="hotel_image">Upload Hotel Image:</label>
    <input type="file" name="hotel_image" id="hotel_image" accept="image/*" required>
    <button type="submit" name="upload_image">Upload Image</button>
</form>

<!-- Display existing images with delete option -->
<h4>Existing Hotel Images</h4>
<ul>
    <?php foreach ($hotel_images as $image): ?>
        <li>
            <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="Hotel Image" width="100">
            <form method="POST" action="hotel_dashboard.php" style="display:inline;">
                <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                <button type="submit" name="delete_image">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>



        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Show Already Assigned Destinations -->
        <h3>Destinations Assigned to Your Hotel</h3>
        <ul>
            <?php if (empty($assigned_destinations)): ?>
                <li>No destinations assigned to your hotel yet.</li>
            <?php else: ?>
                <?php foreach ($assigned_destinations as $destination): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($destination['desti_name']); ?></strong>  
                        (City: <?php echo htmlspecialchars($destination['city']); ?>)
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <button onclick="location.href='assign_desti.php'">Assign New Destination</button>


        <!-- Add a New Room Section -->
        <h3>Add a New Room</h3>
        <form action="add_room.php" method="POST" enctype="multipart/form-data">
            <label for="room_number">Room Number</label>
            <input type="text" id="room_number" name="room_number" required>

            <label for="room_name">Room Name</label>
            <input type="text" id="room_name" name="room_name">

            <label for="facilities">Facilities</label>
            <textarea id="facilities" name="facilities" required></textarea>

            <label for="price_per_night">Price per Night</label>
            <input type="number" id="price_per_night" name="price_per_night" required>

            <label for="max_adults">Max Adults</label>
            <input type="number" id="max_adults" name="max_adults" required>

            <label for="max_children">Max Children</label>
            <input type="number" id="max_children" name="max_children" required>
            
            <label for="availability">Availability</label>
            <select id="availability" name="availability" required>
                <option value="Available">Available</option>
                <option value="Not Available">Not Available</option>
            </select>
            
            <label for="room_images">Room Images (max 5)</label>
            <input type="file" id="room_images" name="room_images[]" accept="image/*" multiple required>
                
            <button type="submit" name="add_room">Add Room</button>
        </form>


        <!-- Manage Rooms Section -->
        <h3>Manage Rooms</h3>
        <table>
            <tr>
                <th>Room Number</th>
                <th>Facilities</th>
                <th>Price/Night</th>
                <th>Max Adults</th>
                <th>Max Children</th>
                <th>Availability</th>
                <th>Booking Status</th>
                <th>Actions</th>
            </tr>
            <?php
            // Fetch rooms for this hotel
            $stmt = $conn->prepare("SELECT * FROM rooms WHERE hotel_id = ?");
            $stmt->bind_param("i", $hotel_id);
            $stmt->execute();
            $rooms = $stmt->get_result();
            $stmt->close();

            while ($room = $rooms->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($room['room_number']); ?></td>
                <td><?php echo htmlspecialchars($room['facilities']); ?></td>
                <td><?php echo htmlspecialchars($room['price_per_night']); ?></td>
                <td><?php echo htmlspecialchars($room['max_adults']); ?></td>
                <td><?php echo htmlspecialchars($room['max_children']); ?></td>
                <td><?php echo htmlspecialchars($room['availability']); ?></td>
                <td>
                    <?php
                    // Fetch all booking date ranges for this room
                    $stmt = $conn->prepare("SELECT start_date, end_date FROM bookings WHERE room_id = ?");
                    $stmt->bind_param("i", $room['room_id']);
                    $stmt->execute();
                    $booking_result = $stmt->get_result();
                    $stmt->close();

                    if ($booking_result->num_rows > 0) {
                        while ($booking = $booking_result->fetch_assoc()) {
                            echo "Booked from " . htmlspecialchars($booking['start_date']) . " to " . htmlspecialchars($booking['end_date']) . "<br>";
                        }
                    } else {
                        echo "No bookings";
                    }
                    ?>
                </td>
                <td>
                    <form action="delete_room.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['room_id']); ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="edit_room.php" method="GET">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['room_id']); ?>">
                        <button type="submit">View & Edit</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
