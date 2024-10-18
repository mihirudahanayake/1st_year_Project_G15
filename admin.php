<?php
// Include the database configuration
include 'config.php';

// Check if the delete parameter is passed in the URL
if (isset($_GET['delete'])) {
    $destination_id = intval($_GET['delete']);
    
    // First, delete associated images from the destination_images table
    $delete_images = "DELETE FROM destination_images WHERE destination_id = $destination_id";

    // Delete the destination from the destinations table
    $delete_query = "DELETE FROM destinations WHERE destination_id = $destination_id";
    if ($conn->query($delete_query) === TRUE) {
        echo "<p>Destination deleted successfully.</p>";
    } else {
        echo "<p>Error deleting destination: " . $conn->error . "</p>";
    }
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

<h1>Site Admin</h1>

<button onclick="location.href='admin_panel.php'">Manage Users & Hotels</button>
<button onclick="location.href='profile.php'">Profile</button>

<div class="dashboard-container">
    <h2>Travel Destinations</h2>
    <div class="card-container">
        <!-- Add New Destination Card -->
        <div class="card add-destination-card">
            <h3>Add New Destination</h3>
            <div class="actions">
                <a href="add-destination.php">Add New</a>
            </div>
        </div>

        <!-- Display Existing Destinations -->
        <?php while ($destination = $destinations->fetch_assoc()): ?>
            <div class="card">
                <h3>
                    <a href="edit_destination.php?id=<?php echo $destination['destination_id']; ?>">
                        <?php echo htmlspecialchars($destination['desti_name']); ?>
                    </a>
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
                    <button onclick="confirmDelete(<?php echo $destination['destination_id']; ?>)">Delete</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
