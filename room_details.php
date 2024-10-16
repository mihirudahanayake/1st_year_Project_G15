<?php
include('config.php');

// Get the room ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $room_id = $conn->real_escape_string($_GET['id']);

    // Fetch room details
    $stmt = $conn->prepare("SELECT rooms.*, hotels.hotel_name AS hotel_name, hotels.location
                            FROM rooms
                            JOIN hotels ON rooms.hotel_id = hotels.hotel_id
                            WHERE rooms.room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $room = $result->fetch_assoc();
    } else {
        echo "<p>Room not found.</p>";
        exit;
    }

    // Fetch all destinations associated with the room
    $stmt = $conn->prepare("SELECT destinations.desti_name
                            FROM hotel_destinations
                            JOIN destinations ON hotel_destinations.destination_id = destinations.destination_id
                            WHERE hotel_destinations.hotel_id = (SELECT hotel_id FROM rooms WHERE room_id = ?)");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $destResult = $stmt->get_result();

    $destinations = [];
    if ($destResult->num_rows > 0) {
        while ($row = $destResult->fetch_assoc()) {
            $destinations[] = $row['desti_name'];
        }
    }

    // Fetch room images
    $stmt = $conn->prepare("SELECT image_path FROM room_images WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $imgResult = $stmt->get_result();

    $images = [];
    if ($imgResult->num_rows > 0) {
        while ($row = $imgResult->fetch_assoc()) {
            $images[] = $row['image_path'];
        }
    }

} else {
    echo "<p>No room specified.</p>";
    exit;
}

// Handle availability check form submission
if (isset($_POST['check_availability'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validate the date range
    if ($start_date && $end_date && $start_date <= $end_date) {
        // Query to check availability
        $stmt = $conn->prepare("SELECT * FROM bookings
                                WHERE room_id = ?
                                  AND (start_date <= ? AND end_date >= ?)");
        $stmt->bind_param("iss", $room_id, $end_date, $start_date);
        $stmt->execute();
        $result = $stmt->get_result();

        $is_available = ($result->num_rows == 0);
    } else {
        echo "<p>Invalid date range.</p>";
        $is_available = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details</title>
    <link rel="stylesheet" href="room_details.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

</head>

<body>

    <div class="background"></div>
    <h1>Room no : <?php echo htmlspecialchars($room['room_number']); ?></h1>
    <div class="container">
        <!-- Swiper Image Gallery -->
        <section class="gallery" id="gallery">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image): ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" class="swiper-slide" alt="Room Image">
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
        
        <section class="room-details">
            <p><strong>Hotel:</strong> <?php echo htmlspecialchars($room['hotel_name']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($room['location']); ?></p>
            <p><strong>Price per Night:</strong> $<?php echo htmlspecialchars($room['price_per_night']); ?></p>
            <p><strong>Max Adults:</strong> <?php echo htmlspecialchars($room['max_adults']); ?></p>
            <p><strong>Max Children:</strong> <?php echo htmlspecialchars($room['max_children']); ?></p>
            <p><strong>Facilities:</strong> <?php echo htmlspecialchars($room['facilities']); ?></p>
        </section>

        <!-- availability -->
        <section class="availability" id="availability">
            <h2>Check Availability</h2>
            <form action="room_details.php?id=<?php echo htmlspecialchars($room_id); ?>" method="POST">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" required>

                <button type="submit" name="check_availability">Check Availability</button>
            </form>

            <?php if (isset($is_available)): ?>
                <?php if ($is_available): ?>
                    <p>The room is available for the selected dates.</p>
                    <form action="book_room.php" method="POST">
                        <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>">
                        <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                        <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                        <button type="submit" name="book_now">Book Now</button>
                    </form>
                <?php else: ?>
                    <p>Sorry, the room is not available for the selected dates.</p>
                <?php endif; ?>
            <?php endif; ?>
        </section>
        
        <section class="destinations" id="destinations">
            <h2>Near Traveling Places</h2>
            <?php if (!empty($destinations)): ?>
                <p><?php echo implode(', ', $destinations); ?></p>
            <?php else: ?>
                <p>No near places found</p>
            <?php endif; ?>
            <button class="back" onclick="location.href='room_list.php'">Back to Hotels</button>
        </section>
        
    </div>
    <script src="room_details.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
</body>
</html>
