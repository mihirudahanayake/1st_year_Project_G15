<?php
include 'config.php';

// Check if a destination ID is passed
if (isset($_GET['id'])) {
    $destination_id = intval($_GET['id']);

    // Fetch destination details from the database
    $result = $conn->query("SELECT * FROM destinations WHERE destination_id = $destination_id");
    $destination = $result->fetch_assoc();

    // Check if form is submitted to update the destination
    if (isset($_POST['update_destination'])) {
        $desti_name = $conn->real_escape_string($_POST['desti_name']);
        $desti_description = $conn->real_escape_string($_POST['desti_description']);
        $city = $conn->real_escape_string($_POST['city']);

        // Update the destination in the database
        $updateQuery = "UPDATE destinations SET desti_name='$desti_name', desti_description='$desti_description', city='$city' WHERE destination_id=$destination_id";
        if ($conn->query($updateQuery) === TRUE) {
            echo "<p>Destination updated successfully.</p>";
        } else {
            echo "<p>Error updating destination: " . $conn->error . "</p>";
        }
    }

    // Handle image deletion
    if (isset($_GET['delete_image'])) {
        $image_id = intval($_GET['delete_image']);
        $deleteImageQuery = "DELETE FROM destination_images WHERE id = $image_id";
        if ($conn->query($deleteImageQuery) === TRUE) {
            echo "<p>Image deleted successfully.</p>";
        } else {
            echo "<p>Error deleting image: " . $conn->error . "</p>";
        }
    }
} else {
    echo "No destination selected for editing.";
    exit;
}

// Fetch images for this destination
$imagesResult = $conn->query("SELECT * FROM destination_images WHERE destination_id = $destination_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
</head>
<body>
    <h1>Edit Destination</h1>

    <!-- Edit destination form -->
    <form action="edit_destination.php?id=<?php echo $destination_id; ?>" method="POST">
        <label for="desti_name">Destination Name</label>
        <input type="text" id="desti_name" name="desti_name" value="<?php echo htmlspecialchars($destination['desti_name']); ?>" required>

        <label for="desti_description">Description</label>
        <textarea id="desti_description" name="desti_description" required><?php echo htmlspecialchars($destination['desti_description']); ?></textarea>

        <label for="city">City</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($destination['city']); ?>" required>

        <button type="submit" name="update_destination">Update Destination</button>
    </form>

    <!-- Show images and delete option -->
    <h2>Images for this Destination</h2>
    <?php if ($imagesResult->num_rows > 0): ?>
        <div>
            <?php while ($image = $imagesResult->fetch_assoc()): ?>
                <div style="display: inline-block; margin: 10px;">
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Image" style="width: 150px; height: auto;">
                    <br>
                    <a href="edit_destination.php?id=<?php echo $destination_id; ?>&delete_image=<?php echo $image['id']; ?>" onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No images found for this destination.</p>
    <?php endif; ?>
</body>
</html>
