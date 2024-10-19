<?php
include_once "config.php";
?>


<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Project</title>
          <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
          <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
          <link rel="stylesheet" type="text/css" href="header.css">
     </head>
     <body>
     <header class="header">
        <nav>
            <div class="navbar">
                <button class="menu-btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </button>
                <h3 class="logo"><span>
                    <span class="t-span1">Travel</span>
                    <span class="t-span2">Mate</span>
                </span></h3>
                <ul id="nav-menu">
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


                    <li><a href="#">About</a></li>

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
                    

                    <li><a href="travel_destination.php">Travel Destinations</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
                
                <?php 
                // Profile or login link based on login status
                if (isset($_SESSION['user_id'])) {
                    echo '<a id="login" href="profile.php"><b>Profile</b></a>';
                } else {
                    echo '<a id="login" href="login.html"><b>Log in</b></a>';
                }
                ?>
            </div>
        </nav>
    </header>

    <!-- Side Navigation -->
    <div class="side-nav" id="side-nav">
        <button class="close-btn" id="close-btn">&times;</button>
        <ul>
               <?php 
                    // Check if the user is logged in
                    if (isset($_SESSION['user_id'])) {
                        // Check the user type and show relevant links
                        if ($_SESSION['user_type'] == 'hotel_admin') {
                            // For hotel admins, hide 'Hotels' and show 'Add New Room'
                            echo '<li><a href="hotel_dashboard.php">Home</a></li>';
                        } else {
                            // For regular users, show 'Hotels'
                            echo '<li><a href="index.php">Home</a></li>';
                        }
                    }
                    ?>
            <li><a href="#">About</a></li>

            <?php 
            // Show relevant links based on user type in the side navigation
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_type'] == 'hotel_admin') {
                    echo '<li><a href="add_room.php">Add New Room</a></li>';
                } else {
                    echo '<li><a href="hotel_list.php">Hotels</a></li>';
                }
            }
            ?>

            <li><a href="travel_destination.php">Travel Destinations</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </div>
     </body>
</html>