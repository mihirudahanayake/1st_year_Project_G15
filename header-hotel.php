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
                              <li><a href="hotel_dashboard.php">Home</a></li>
                              <li><a href="#">About</a></li>
                              <li><a href="hotel_list.php">Hotels</a></li>
                              <li><a href="travel_destination.php">Travel Destinations</a></li>
                              <li><a href="#">Contact Us</a></li>
                         </ul>
                         <?php 
                              if (isset($_SESSION['user_id'])) 
                              {
                                   echo '<a id="login" href="profile.php"><b>Profile</b></a>';
                              }
                              else {
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="hotel_list.php">Hotels</a></li>
                    <li><a href="travel_destination.php">Travel Destinations</a></li>
                    <li><a href="#">Contact Us</a></li>
               </ul>
          </div>
     </body>
</html>