<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>My Webpage</title>
      <link rel="stylesheet" href="footer.css">
   </head>
   <body>
      <footer>
         <div class="footer-container">
            <div class="footer-column">
                  <h2>Travel<span>Mate</span></h2>
                  <p>Discover the beauty of Sri Lanka with our easy-to-use travel website. From stunning destinations to the best hotel deals, we are here to help you plan your perfect getaway. Book your dream vacation today and explore the wonders of Sri Lanka!</p>
                  <div class="social-icons">
                     <a href="#"><i class="fab fa-facebook-f"></i></a>
                     <a href="#"><i class="fab fa-linkedin-in"></i></a>
                     <a href="#"><i class="fab fa-twitter"></i></a>
                  </div>
            </div>
            <div class="footer-column">
                  <h3>- Quick link -</h3>
                  <ul>
                     <?php 
                        // Check if the user is logged in
                        if (isset($_SESSION['user_id'])) {
                              if ($_SESSION['user_type'] == 'hotel_admin') {
                                 echo '<li><a href="hotel_dashboard.php">Home</a></li>';
                              } elseif ($_SESSION['user_type'] == 'user') {
                                 echo '<li><a href="index.php">Home</a></li>';
                              } else {
                                 echo '<li><a href="index.php">Home</a></li>';
                              }
                        } else {
                              echo '<li><a href="index.php">Home</a></li>';
                        }
                     ?>
                     <li><a href="about.php">About Us</a></li>
                     <?php 
                    // Check if the user is logged in
                    if (isset($_SESSION['user_id'])) {
                         // Check the user type and show relevant links
                         if ($_SESSION['user_type'] == 'hotel_admin') {
                            // For hotel admins, hide 'Hotels' and show 'Add New Room'
                            echo '<li><a href="add_room.php">Add New Room</a></li>';
                         } elseif ($_SESSION['user_type'] == 'user') {
                              echo '<li><a href="hotel_list.php">Hotels</a></li>';
                         } else {
                              echo '<li><a href="hotel_list.php">Hotels</a></li>';
                         }
                    } else {
                         echo '<li><a href="hotel_list.php">Hotels</a></li>';
                     }
                    ?>
                     <li><a href="travel_destination.php">Destinations</a></li>
                     <li><a href="#">Contact Us</a></li>
                  </ul>
            </div>
            <!-- <div class="footer-column">
                  <h3>Our Team</h3>
                  <div class="destination-images">
                     <img src="images/bg2.jpg" alt="Destination 1">
                     <img src="destination2.jpg" alt="Destination 2">
                     <img src="destination3.jpg" alt="Destination 3">
                     <img src="destination4.jpg" alt="Destination 4">
                     <img src="destination5.jpg" alt="Destination 5">
                     <img src="destination6.jpg" alt="Destination 6">
                  </div>
            </div> -->
            <div class="footer-column">
               <div class="ct">
                  <h3> -  Contact  -</h3>
                  <p><i class="fas fa-map-marker-alt"></i> Any ware, any rode, Sri Lanka</p>
                  <p><i class="fas fa-phone-alt"></i> +91 70 48 68 401</p>
                  <p><i class="fas fa-envelope"></i> travelmate.g15@gmail.com</p>
               </div>
            </div>
         </div>
         <div class="footer-bottom">
            <p>travelmate &copy; 2024 all right reserve</p>
         </div>
      </footer>

   </body>
</html>