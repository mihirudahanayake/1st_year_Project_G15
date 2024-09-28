<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Destinations</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Explore Our Travel Destinations</h1>

        <!-- Search Form -->
        <form action="travel_destination.php" method="GET">
            <label for="city">Search by City</label>
            <select id="city" name="city">
                <option value="">Select a city</option>
                <?php
                include('config.php');

                // Fetch cities from the cities table
                $cityQuery = "SELECT DISTINCT city_name FROM cities";
                $cityResult = $conn->query($cityQuery);

                if ($cityResult->num_rows > 0) {
                    while ($row = $cityResult->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['city_name']) . '">' . htmlspecialchars($row['city_name']) . '</option>';
                    }
                }
                ?>
            </select>

            <label for="desti_name">Search by Destination Name</label>
            <input type="text" id="desti_name" name="desti_name" placeholder="Enter destination name">

            <button type="submit">Search</button>
        </form>

        <div class="destinations-grid">
            <?php
            // Base query to fetch all destinations
            $query = "SELECT d.destination_id, d.desti_name, d.desti_description, d.city 
                      FROM destinations d WHERE 1=1";

            // Apply city filter if provided
            if (isset($_GET['city']) && !empty($_GET['city'])) {
                $city = $conn->real_escape_string($_GET['city']);
                $query .= " AND d.city LIKE '%$city%'";
            }

            // Apply destination name filter if provided
            if (isset($_GET['desti_name']) && !empty($_GET['desti_name'])) {
                $desti_name = $conn->real_escape_string($_GET['desti_name']);
                $query .= " AND d.desti_name LIKE '%$desti_name%'";
            }

            // Execute the destination query
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($destination = $result->fetch_assoc()) {
                    echo "<div class='destination'>";
                    echo "<h2>" . htmlspecialchars($destination['desti_name']) . "</h2>";
                    echo "<p>" . htmlspecialchars($destination['city']) . "</p>";

                    // Fetch images for the current destination
                    $destination_id = intval($destination['destination_id']);
                    $imageQuery = "SELECT image_url FROM destination_images WHERE destination_id = $destination_id LIMIT 1";
                    $imageResult = $conn->query($imageQuery);

                    // Display the first image if found
                    if ($imageResult->num_rows > 0) {
                        $image = $imageResult->fetch_assoc();
                        echo "<img src='" . htmlspecialchars($image['image_url']) . "' alt='" . htmlspecialchars($destination['desti_name']) . "'>";
                    } else {
                        // If no image, show a placeholder
                        echo "<img src='default_image.jpg' alt='No image available'>";
                    }

                    echo "<p>" . htmlspecialchars($destination['desti_description']) . "</p>";
                    // Add a View Details button
                    echo '<a href="destination_details.php?id=' . htmlspecialchars($destination['destination_id']) . '" class="details-link">View Details</a>';
                    echo "</div>";
                }
            } else {
                echo "<p>No destinations found matching your criteria.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
