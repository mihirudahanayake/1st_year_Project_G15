<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Listing</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <form action="room_list.php" method="GET">
        <label for="city">City</label>
        <select id="city" name="city">
            <option value="">Select a city</option>
            <?php
            include('config.php');

            // Fetch unique city names for the dropdown list
            $cityQuery = "SELECT DISTINCT city_name FROM cities";
            $cityResult = $conn->query($cityQuery);

            if ($cityResult->num_rows > 0) {
                while ($row = $cityResult->fetch_assoc()) {
                    echo '<option value="' . $row['city_name'] . '">' . $row['city_name'] . '</option>';
                }
            }

            ?>
        </select>

        <label for="destination">Destination</label>
        <select id="destination" name="destination">
            <option value="">Select a destination</option>
            <?php
            // Fetch all destinations for the dropdown list
            $destQuery = "SELECT destination_id, desti_name FROM destinations";
            $destResult = $conn->query($destQuery);

            if ($destResult->num_rows > 0) {
                while ($row = $destResult->fetch_assoc()) {
                    echo '<option value="' . $row['desti_name'] . '">' . $row['desti_name'] . '</option>';
                }
            }

            ?>
        </select>

        <label for="min_price">Min Price</label>
        <input type="number" id="min_price" name="min_price" step="0.01" placeholder="Minimum price per night">

        <label for="max_price">Max Price</label>
        <input type="number" id="max_price" name="max_price" step="0.01" placeholder="Maximum price per night">

        <button type="submit">Search</button>
    </form>

    <?php
    // Include database configuration
    include('config.php');

    // Base query to fetch rooms and related hotel information
    $query = "SELECT rooms.room_id, rooms.room_name, rooms.room_description, rooms.price_per_night, 
                     hotels.hotel_name AS hotel_name, hotels.location, 
                     GROUP_CONCAT(DISTINCT destinations.desti_name ORDER BY destinations.desti_name ASC SEPARATOR ', ') AS desti_names 
              FROM rooms 
              JOIN hotels ON rooms.hotel_id = hotels.hotel_id 
              JOIN hotel_destinations ON hotels.hotel_id = hotel_destinations.hotel_id
              JOIN destinations ON hotel_destinations.destination_id = destinations.destination_id
              JOIN cities ON hotels.city_id = cities.city_id
              WHERE 1=1";

    // Add filtering criteria
    if (isset($_GET['city']) && !empty($_GET['city'])) {
        $city = $conn->real_escape_string($_GET['city']);
        $query .= " AND cities.city_name LIKE '%$city%'";
    }

    if (isset($_GET['destination']) && !empty($_GET['destination'])) {
        $destination = $conn->real_escape_string($_GET['destination']);
        $query .= " AND destinations.desti_name LIKE '%$destination%'";
    }

    if (isset($_GET['min_price']) && !empty($_GET['min_price'])) {
        $min_price = $conn->real_escape_string($_GET['min_price']);
        $query .= " AND rooms.price_per_night >= $min_price";
    }

    if (isset($_GET['max_price']) && !empty($_GET['max_price'])) {
        $max_price = $conn->real_escape_string($_GET['max_price']);
        $query .= " AND rooms.price_per_night <= $max_price";
    }

    $query .= " GROUP BY rooms.room_id";

    // Execute the query
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($rooms = $result->fetch_assoc()) {
            echo "<div class='room'>";
            echo "<h3>" . $rooms['hotel_name'] . "</h3>";
            echo "<p>Description: " . $rooms['room_description'] . "</p>";
            echo "<p>Price per Night: $" . $rooms['price_per_night'] . "</p>";
            echo "<p>Hotel: " . $rooms['hotel_name'] . "</p>";
            echo "<p>Location: " . $rooms['location'] . "</p>";
            echo "<p>Destinations: " . $rooms['desti_names'] . "</p>";
            echo '<a href="room_details.php?id=' . $rooms['room_id'] . '" class="details-link">View Details</a>';
            echo "</div>";
        }
    } else {
        echo "<p>No rooms found matching your criteria.</p>";
    }

    $conn->close();
    ?>

</body>
</html>
