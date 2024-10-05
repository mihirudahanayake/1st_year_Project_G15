<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Listing</title>
    <link rel="stylesheet" href="room_list.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
    <div class="bg"></div>
    <?php include 'header.php'; ?>

    <!-- Search Form -->
    <form action="room_list.php" method="GET">
        <label for="city">City</label>
        <select id="city" name="city">
            <option value="">Select a city</option>
            <?php

            // Fetch unique city names for the dropdown list from the cities table
            $cityQuery = "SELECT city_name FROM cities";
            $cityResult = $conn->query($cityQuery);

            if ($cityResult->num_rows > 0) {
                while ($row = $cityResult->fetch_assoc()) {
                    echo '<option value="' . $row['city_name'] . '">' . $row['city_name'] . '</option>';
                }
            } else {
                echo '<option value="">No cities available</option>';
            }

            ?>
        </select>

        <label for="destination">Destination</label>
        <select id="destination" name="destination">
            <option value="">Select a destination</option>
            <?php
            // Fetch all destinations for the dropdown list
            $destQuery = "SELECT desti_name FROM destinations";
            $destResult = $conn->query($destQuery);

            if ($destResult->num_rows > 0) {
                while ($row = $destResult->fetch_assoc()) {
                    echo '<option value="' . $row['desti_name'] . '">' . $row['desti_name'] . '</option>';
                }
            } else {
                echo '<option value="">No destinations available</option>';
            }

            ?>
        </select>

        <label for="min_price">Min Price</label>
        <input type="number" id="min_price" name="min_price" step="0.01" placeholder="Minimum price per night">

        <label for="max_price">Max Price</label>
        <input type="number" id="max_price" name="max_price" step="0.01" placeholder="Maximum price per night">

        <button type="submit">Search</button>
    </form>

    <!-- Room Listing Section -->
    <div class="room-container">
        <?php

        // Base query to fetch rooms, hotel details, and the first image
        $query = "SELECT DISTINCT rooms.room_id, rooms.room_name, rooms.facilities, rooms.price_per_night, 
                 hotels.hotel_name, hotels.location, 
                 (SELECT image_path FROM room_images WHERE room_images.room_id = rooms.room_id ORDER BY image_id ASC LIMIT 1) AS first_image 
          FROM rooms 
          JOIN hotels ON rooms.hotel_id = hotels.hotel_id
          LEFT JOIN hotel_destinations ON hotels.hotel_id = hotel_destinations.hotel_id
          LEFT JOIN destinations ON hotel_destinations.destination_id = destinations.destination_id
          WHERE 1=1";

        if (isset($_GET['city']) && !empty($_GET['city'])) {
            $city = $conn->real_escape_string($_GET['city']);
            $query .= " AND hotels.location = '$city'";
        }

        if (isset($_GET['destination']) && !empty($_GET['destination'])) {
            $destination = $conn->real_escape_string($_GET['destination']);
            $query .= " AND destinations.desti_name = '$destination'";
        }

        if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
            $min_price = $conn->real_escape_string($_GET['min_price']);
            $query .= " AND rooms.price_per_night >= $min_price";
        }

        if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
            $max_price = $conn->real_escape_string($_GET['max_price']);
            $query .= " AND rooms.price_per_night <= $max_price";
        }

        $query .= " ORDER BY rooms.room_id";

        echo "<!-- SQL Query: $query -->";

        $result = $conn->query($query);

        if ($result === false) {
            echo "<p>Error executing query: " . $conn->error . "</p>";
        } else if ($result->num_rows > 0) {
            while ($room = $result->fetch_assoc()) {
                echo "<a href='room_details.php?id=" . $room['room_id'] . "' class='view-details'>";
                echo "<div class='room'>";
                echo "<img src='" . $room['first_image'] . "' alt='Room Image' class='room-image'>";
                echo "<div class='room-details'>";
                echo "<h3>" . $room['hotel_name'] . "</h3>";
                echo "<p>Price per Night: $" . $room['price_per_night'] . "</p>";
                echo "<p>Hotel: " . $room['hotel_name'] . "</p>";
                echo "<p>Location: " . $room['location'] . "</p>";
                echo "<p>Facilities: " . $room['facilities'] . "</p>";
                echo "</div>";
                echo "</div>";
                echo "</a>";
            }
        } else {
            echo "<p>No rooms available.</p>";
        }

        $conn->close();
        ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
