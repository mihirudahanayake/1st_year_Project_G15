<?php
include('config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $destination_id = $conn->real_escape_string($_GET['id']);

    // Fetch the destination details
    $query = "SELECT * FROM destinations WHERE destination_id = $destination_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
    } else {
        echo "<p>Destination not found.</p>";
        exit;
    }
} else {
    echo "<p>No destination specified.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Destination</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Destination</h1>

        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="destination_id" value="<?php echo $destination['destination_id']; ?>">
            <input type="hidden" name="existing_image_url" value="<?php echo $destination['image_url']; ?>">

            <label for="desti_name">Destination Name</label>
            <input type="text" id="desti_name" name="desti_name" value="<?php echo $destination['desti_name']; ?>" required>

            <label for="desti_description">Description</label>
            <textarea id="desti_description" name="desti_description" required><?php echo $destination['desti_description']; ?></textarea>

            <label for="city">City</label>
            <input type="text" id="city" name="city" value="<?php echo $destination['city']; ?>" required>

            <label for="image">Image (Leave blank to keep current image)</label>
            <input type="file" id="image" name="image">

            <button type="submit" name="edit_destination">Update Destination</button>
        </form>

        <a href="admin.php">Back to Destinations</a>
    </div>
</body>
</html>
