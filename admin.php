<?php
include('config.php');

// Handle form submission to add a new destination
if (isset($_POST['add_destination'])) {
    $desti_name = $conn->real_escape_string($_POST['desti_name']);
    $desti_description = $conn->real_escape_string($_POST['desti_description']);
    $city = $conn->real_escape_string($_POST['city']);

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validate file extension and size
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize) {
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_url = $dest_path;
            } else {
                echo "<p>There was an error uploading the file.</p>";
                exit;
            }
        } else {
            echo "<p>Invalid file extension or file too large.</p>";
            exit;
        }
    } else {
        $image_url = ''; // Default image URL or handle accordingly
    }

    $query = "INSERT INTO destinations (desti_name, desti_description, city, image_url) 
              VALUES ('$desti_name', '$desti_description', '$city', '$image_url')";
    if ($conn->query($query) === TRUE) {
        echo "<p>Destination added successfully.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Handle request to delete a destination
if (isset($_GET['delete'])) {
    $destination_id = $conn->real_escape_string($_GET['delete']);
    $query = "DELETE FROM destinations WHERE destination_id = $destination_id";
    if ($conn->query($query) === TRUE) {
        echo "<p>Destination deleted successfully.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Handle form submission to edit a destination
if (isset($_POST['edit_destination'])) {
    $destination_id = $conn->real_escape_string($_POST['destination_id']);
    $desti_name = $conn->real_escape_string($_POST['desti_name']);
    $desti_description = $conn->real_escape_string($_POST['desti_description']);
    $city = $conn->real_escape_string($_POST['city']);

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validate file extension and size
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        if (in_array($fileExtension, $allowedExtensions) && $fileSize <= $maxFileSize) {
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_url = $dest_path;
            } else {
                echo "<p>There was an error uploading the file.</p>";
                exit;
            }
        } else {
            echo "<p>Invalid file extension or file too large.</p>";
            exit;
        }
    } else {
        $image_url = $conn->real_escape_string($_POST['existing_image_url']); // Retain the existing image URL
    }

    $query = "UPDATE destinations 
              SET desti_name='$desti_name', desti_description='$desti_description', city='$city', image_url='$image_url' 
              WHERE destination_id=$destination_id";
    if ($conn->query($query) === TRUE) {
        echo "<p>Destination updated successfully.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Fetch all destinations for display
$destinations = $conn->query("SELECT * FROM destinations");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Site Admin</h1>

        <button><a href="admin_panel.php">Manage</a></button>

        <!-- Form to Add a New Destination -->
        <h2>Manage Travel Destinations</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label for="desti_name">Destination Name</label>
            <input type="text" id="desti_name" name="desti_name" required>

            <label for="desti_description">Description</label>
            <textarea id="desti_description" name="desti_description" required></textarea>

            <label for="city">City</label>
            <input type="text" id="city" name="city" required>

            <label for="image">Image</label>
            <input type="file" id="image" name="image" required>

            <button type="submit" name="add_destination">Add Destination</button>
        </form>

        <!-- Display Existing Destinations -->
        <h2>Existing Destinations</h2>
        <?php if ($destinations->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>City</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($destination = $destinations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $destination['destination_id']; ?></td>
                            <td><?php echo $destination['desti_name']; ?></td>
                            <td><?php echo $destination['desti_description']; ?></td>
                            <td><?php echo $destination['city']; ?></td>
                            <td><img src="<?php echo $destination['image_url']; ?>" alt="<?php echo $destination['desti_name']; ?>" style="width: 100px;"></td>
                            <td>
                                <a href="edit_destination.php?id=<?php echo $destination['destination_id']; ?>">Edit</a> | 
                                <a href="admin.php?delete=<?php echo $destination['destination_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No destinations found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
