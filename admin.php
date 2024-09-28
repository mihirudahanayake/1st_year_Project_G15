<?php
// Include the database configuration
include 'config.php';

// Handle form submission to add a new destination
if (isset($_POST['add_destination'])) {
    $desti_name = $conn->real_escape_string($_POST['desti_name']);
    $desti_description = $conn->real_escape_string($_POST['desti_description']);
    $city = $conn->real_escape_string($_POST['city']);

    // Insert the destination into the destinations table
    $query = "INSERT INTO destinations (desti_name, desti_description, city) 
              VALUES ('$desti_name', '$desti_description', '$city')";
    if ($conn->query($query) === TRUE) {
        $destination_id = $conn->insert_id; // Get the last inserted ID

        // Handle file uploads
        if (isset($_FILES['images']) && $_FILES['images']['error'][0] === UPLOAD_ERR_OK) {
            $uploadFileDir = './uploads/';
            
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $fileTmpPath = $_FILES['images']['tmp_name'][$key];
                $fileName = $_FILES['images']['name'][$key];
                $fileSize = $_FILES['images']['size'][$key];
                $fileType = $_FILES['images']['type'][$key];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                // Validate file extension and size
                $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
                $maxFileSize = 5 * 1024 * 1024; // 5 MB

                if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize) {
                    $dest_path = $uploadFileDir . $fileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Insert image URL into the database
                        $query = "INSERT INTO destination_images (destination_id, image_url) 
                                  VALUES ('$destination_id', '$dest_path')";
                        $conn->query($query);
                    } else {
                        echo "<p>There was an error uploading the file.</p>";
                    }
                } else {
                    echo "<p>Invalid file extension or file too large.</p>";
                }
            }
        } else {
            echo "<p>No images uploaded or error occurred.</p>";
        }

        echo "<p>Destination added successfully.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Handle deletion of a destination
if (isset($_GET['delete'])) {
    $destination_id = intval($_GET['delete']);
    $conn->query("DELETE FROM destination_images WHERE destination_id = $destination_id");
    $conn->query("DELETE FROM destinations WHERE destination_id = $destination_id");
    echo "<p>Destination deleted successfully.</p>";
}

// Fetch all destinations
$destinations = $conn->query("SELECT * FROM destinations");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h1>Site Admin</h1>

    <button><a href="admin_panel.php">Manage</a></button>

    <h1>Destinations</h1>


    <!-- Form to Add a New Destination -->
    <form action="admin.php" method="POST" enctype="multipart/form-data">
        <label for="desti_name">Destination Name</label>
        <input type="text" id="desti_name" name="desti_name" required>

        <label for="desti_description">Description</label>
        <textarea id="desti_description" name="desti_description" required></textarea>

        <label for="city">City</label>
        <input type="text" id="city" name="city" required>

        <label for="images">Images</label>
        <input type="file" id="images" name="images[]" multiple required>

        <button type="submit" name="add_destination">Add Destination</button>
    </form>

    <h2>Existing Destinations</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>City</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($destination = $destinations->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $destination['destination_id']; ?></td>
                    <td><?php echo htmlspecialchars($destination['desti_name']); ?></td>
                    <td><?php echo htmlspecialchars($destination['desti_description']); ?></td>
                    <td><?php echo htmlspecialchars($destination['city']); ?></td>
                    <td>
                        <?php
                        // Fetch images for the destination
                        $images = $conn->query("SELECT * FROM destination_images WHERE destination_id = " . $destination['destination_id']);
                        while ($image = $images->fetch_assoc()):
                        ?>
                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Image" style="width: 100px; height: auto;">
                        <?php endwhile; ?>
                    </td>
                    <td>
                        <!-- Add the Edit button linking to edit_destination.php with the destination ID -->
                        <a href="edit_destination.php?id=<?php echo $destination['destination_id']; ?>">Edit</a>
                        <a href="admin.php?delete=<?php echo $destination['destination_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>

    </table>
</body>
</html>
