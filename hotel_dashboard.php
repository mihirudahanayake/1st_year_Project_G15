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

        <button onclick="location.href='hadminprofile.php'">Profile</button>

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
                        - <?php echo htmlspecialchars($destination['desti_description']); ?> 
                        (City: <?php echo htmlspecialchars($destination['city']); ?>)
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <!-- Select or Add City -->
        <h3>Select City</h3>
        <form method="POST" action="hotel_dashboard.php">
            <label for="city">Select City:</label>
            <select name="city" id="city" onchange="this.form.submit()">
                <option value="">Select a city</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo htmlspecialchars($city['city_name']); ?>"
                        <?php echo isset($_POST['city']) && $_POST['city'] === $city['city_name'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($city['city_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Add New City -->
        <h3>Add New City</h3>
        <form method="POST" action="hotel_dashboard.php">
            <label for="new_city">New City:</label>
            <input type="text" id="new_city" name="new_city" required>
            <button type="submit" name="add_city">Add City</button>
        </form>

        <!-- Add Destination Based on Selected City -->
        <?php if (isset($_POST['city'])): ?>
            <h3>Select or Add Destination</h3>
            <form method="POST" action="hotel_dashboard.php">
                <input type="hidden" name="city" value="<?php echo htmlspecialchars($_POST['city']); ?>">
                <label for="destination_id">Select Destination:</label>
                <select name="destination_id" id="destination_id">
                    <?php foreach ($destinations as $destination): ?>
                        <option value="<?php echo htmlspecialchars($destination['destination_id']); ?>">
                            <?php echo htmlspecialchars($destination['desti_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="assign_destination">Assign to Hotel</button>
            </form>

            <?php if (isset($_POST['city']) && !empty($_POST['city'])): ?>
    <h3>Add New Destination</h3>
    <form action="new_desti.php" method="POST" enctype="multipart/form-data">
        <label for="desti_name">Destination Name</label>
        <input type="text" id="desti_name" name="desti_name" required>

        <label for="desti_description">Description</label>
        <textarea id="desti_description" name="desti_description" required></textarea>

        <label for="city">City</label>
        <select id="city" name="city" required>
            <?php
            // Fetch cities again for the dropdown
            $cities_stmt = $conn->prepare("SELECT * FROM cities");
            $cities_stmt->execute();
            $cities_result = $cities_stmt->get_result();
            $cities_stmt->close();

            while ($city = $cities_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($city['city_name']); ?>"
                    <?php echo $_POST['city'] === $city['city_name'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($city['city_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="image">Image</label>
        <input type="file" id="image" name="image" required>

        <button type="submit" name="add_destination">Add Destination</button>
    </form>
<?php endif; ?>
        <?php endif; ?>
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
                        <button type="submit">Edit</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
