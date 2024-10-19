<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" type="text/css" href="about.css">
</head>
<body>
    <div class="bg"></div>
    <?php include('header.php'); ?>
    <section class="travel">
        <div class="heading">
            <h1>About Us</h1>
        </div>
        <div class="container">
            <div class="travel-content">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                    Ratione possimus autem eum repellendus quos corporis, doloribus magni esse quae, 
                    itaque dignissimos dicta iusto? Deleniti, sequi? Facere, repudiandae! Nisi, natus labore!
                </p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
                    Ratione possimus autem eum repellendus quos corporis, doloribus magni esse quae, 
                    itaque dignissimos dicta iusto? Deleniti, sequi? Facere, repudiandae! Nisi, natus labore!
                </p>
            </div>
            <div class="travel-image">
                <img id="travelImg" src="images/bg4.jpg" alt="Travel Image" class="image-popup-trigger">
                <img id="travelImg2" src="images/bg2.jpg" alt="Travel Image" class="image-popup-trigger">
                <img id="travelImg3" src="images/bg3.jpg" alt="Travel Image" class="image-popup-trigger">
            </div>
        </div>
    </section>

    <!-- Image Popup Modal -->
    <div id="popupModal" class="popup-modal">
        <span class="close-popup">&times;</span>
        <img class="popup-content" id="imgPopup">
    </div>
    <?php include('footer.php'); ?>

    <script>
        const modal = document.getElementById("popupModal");
        const images = document.querySelectorAll('.travel-image img'); // Select all images
        const modalImg = document.getElementById("imgPopup");
        const closePopup = document.getElementsByClassName("close-popup")[0];
        let currentImageIndex = 0;

        // Function to show the current image in the modal
        function showImageInModal(index) {
            modal.style.display = "block";
            modalImg.src = images[index].src;
        }

        // Click event to show image in modal
        images.forEach((img, index) => {
            img.onclick = function() {
                showImageInModal(index);
            }
        });

        closePopup.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Function to change images automatically
        function changeImage() {
            // Hide all images
            images.forEach(img => img.style.display = 'none');
            
            // Show current image
            images[currentImageIndex].style.display = 'block';

            // Update index for next image
            currentImageIndex = (currentImageIndex + 1) % images.length; // Loop back to first image
        }

        // Initial call to display the first image
        changeImage();
        // Set interval to change images every 3 seconds
        setInterval(changeImage, 3000); // Change time (in ms) as needed
    </script>
</body>
</html>
