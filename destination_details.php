<?php
include('config.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $destination_id = $conn->real_escape_string($_GET['id']);

    // Fetch the destination details
    $query = "SELECT desti_name, desti_description FROM destinations WHERE destination_id = $destination_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
    } else {
        echo "<p>Destination not found.</p>";
        exit;
    }

    // Fetch associated images
    $image_query = "SELECT image_url FROM destination_images WHERE destination_id = $destination_id";
    $image_result = $conn->query($image_query);

    $images = [];
    if ($image_result->num_rows > 0) {
        while ($image = $image_result->fetch_assoc()) {
            $images[] = $image['image_url'];
        }
    }

    // Fetch available hotels
    $hotels_query = "
        SELECT DISTINCT hotels.hotel_id, hotels.hotel_name, hotels.description, hotels.location 
        FROM hotels
        JOIN hotel_destinations ON hotels.hotel_id = hotel_destinations.hotel_id
        WHERE hotel_destinations.destination_id = $destination_id
    ";
    $hotels_result = $conn->query($hotels_query);

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
    <title><?php echo htmlspecialchars($destination['desti_name']); ?> - Details</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="destination_details.css">
</head>
<body>

    <div class="bg"></div>
    <?php include('header.php');?>
    <div class="container">
        <h1><?php echo htmlspecialchars($destination['desti_name']); ?></h1>

        <section class="gallery" id="gallery">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="swiper-slide" data-index="<?php echo $index; ?>">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Image of <?php echo htmlspecialchars($destination['desti_name']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div> <!-- Add this line for pagination -->
            </div>
        </section>

        <!-- Thumbnail Section -->
        <div class="thumbnails">
            <?php foreach ($images as $index => $image): ?>
                <img src="<?php echo htmlspecialchars($image); ?>" alt="Thumbnail of <?php echo htmlspecialchars($destination['desti_name']); ?>" data-index="<?php echo $index; ?>">
            <?php endforeach; ?>
        </div>

        <p><?php echo htmlspecialchars($destination['desti_description']); ?></p>

        <h2>Available Hotels near <?php echo htmlspecialchars($destination['desti_name']); ?></h2>
        <div class="hotels-list">
            <?php
            if ($hotels_result->num_rows > 0) {
                while ($hotel = $hotels_result->fetch_assoc()) {
                    echo "<div class='hotel'>";
                    echo "<h3>" . htmlspecialchars($hotel['hotel_name']) . "</h3>";
                    echo "<p>" . htmlspecialchars($hotel['description']) . "</p>";
                    echo "<p><strong>Location:</strong> " . htmlspecialchars($hotel['location']) . "</p>";
                    echo '<a href="room_list.php?id=' . htmlspecialchars($hotel['hotel_id']) . '" class="details-link">View Details</a>';
                    echo "</div>";
                }
            } else {
                echo "<p>No available hotels near this destination.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper', {
            loop: true,
            effect: "fade",
            slidesPerView: 2,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        // Thumbnail click event to change the active slide
        const thumbnails = document.querySelectorAll('.thumbnails img');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function () {
                const index = this.getAttribute('data-index');
                swiper.slideTo(index);
            });
        });
    </script>
    <?php include('footer.php'); ?>
</body>
</html>

<?php
$conn->close();
?>
