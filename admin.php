<?php
// Include the database configuration
include 'config.php';

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
</head>
<body>

<h1>Site Admin</h1>

<button onclick="location.href='admin_panel.php'">Manage Users & Hotels</button>
<button onclick="location.href='profile.php'">Profile</button>
<h1>Destinations</h1>

<div class="dashboard-container">
    <h2>Existing Destinations</h2>
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
                <p><?php echo htmlspecialchars($destination['desti_description']); ?></p>
                <div>
                    <?php
                    // Fetch the first image for the destination
                    $images = $conn->query("SELECT * FROM destination_images WHERE destination_id = " . $destination['destination_id'] . " LIMIT 1");
                    if ($image = $images->fetch_assoc()): ?>
                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Image">
                    <?php endif; ?>
                </div>
                <div class="actions">
                    <a href="edit_destination.php?id=<?php echo $destination['destination_id']; ?>">Edit</a>
                    <a href="admin.php?delete=<?php echo $destination['destination_id']; ?>">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
