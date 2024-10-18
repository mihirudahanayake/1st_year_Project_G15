<?php
// Include the database configuration
include 'config.php';

// Check if the delete parameter is passed in the URL
if (isset($_GET['delete'])) {
    $destination_id = intval($_GET['delete']);
    
    // First, delete associated images from the destination_images table
    $delete_images = "DELETE FROM destination_images WHERE destination_id = ?";
    $stmt = $conn->prepare($delete_images);
    $stmt->bind_param("i", $destination_id);
    if (!$stmt->execute()) {
        echo "<p>Error deleting images: " . $stmt->error . "</p>";
    }
    $stmt->close();

    // Then, delete the destination from the destinations table
    $delete_query = "DELETE FROM destinations WHERE destination_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $destination_id);
    if ($stmt->execute()) {
        echo "<p>Destination deleted successfully.</p>";
    } else {
        echo "<p>Error deleting destination: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Fetch all destinations
$destinations = $conn->query("SELECT * FROM destinations");

// Fetch all cities
$cities = $conn->query("SELECT * FROM cities");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <script>
        function confirmDelete(destinationId) {
            if (confirm("Are you sure you want to delete this destination?")) {
                window.location.href = "admin.php?delete=" + destinationId;
            }
        }
    </script>
</head>
<body>

    <div class="bg"></div>
    <div class="dashboard-container">
    <h1>Site Admin</h1>
    <div class="button-container">
        <button style="font-size: 16px;" onclick="location.href='admin_panel.php'">Manage Users & Hotels</button>
        <button style="font-size: 16px;" onclick="location.href='profile.php'" id="profile">Profile</button>
    </div>

    
        <h2>Travel Destinations</h2>
        <!-- Add New Destination Card -->
        <div class="add-destination-card">
            <div class="actions">
                <a href="add-destination.php">Add New</a>
            </div>
        </div>
        <br>

        <div class="card-container">


            <!-- Display Existing Destinations -->
            <?php while ($destination = $destinations->fetch_assoc()): ?>
                <div class="card">
                    <h3>
                        
                            <?php echo htmlspecialchars($destination['desti_name']); ?>

                    </h3>
                    <div>
                        <?php
                        // Fetch the first image for the destination
                        $images = $conn->query("SELECT * FROM destination_images WHERE destination_id = " . $destination['destination_id'] . " LIMIT 1");
                        if ($image = $images->fetch_assoc()): ?>
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Image">
                        <?php endif; ?>
                    </div>
                    <div class="actions">
                        <a href="edit_destination.php?id=<?php echo $destination['destination_id']; ?>">View & Edit</a>
                        <!-- Add the delete button with a confirmation prompt -->
                        <button onclick="confirmDelete(<?php echo $destination['destination_id']; ?>)" id="dlt">Delete</button>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
