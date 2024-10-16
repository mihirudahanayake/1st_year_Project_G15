<?php
include_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="header.css">
    <link rel="stylesheet" type="text/css" href="index.css">
    
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
                    <li><a href="#home">Home</a></li>
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
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="hotel_list.php">Hotels</a></li>
            <li><a href="travel_destination.php">Travel Destinations</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </div>

    <!--home section start-->
    <section class="home" id="home">
        <h1 class="mhead">Let's Create <br/>Memorable <br/>Journey</h1>
        <div class="row">
            <div class="content">
                <h1 class="head">Let's Create <br/>Memorable <br/>Journey</h1>
                <p id="p11">Escape to a world of wonder with our travel experiences tailored just for you.
                    Whether you seek the thrill of adventure, the serenity of pristine beaches, or the
                    charm of historic cities, our curated itineraries promise unforgettable memories. Dive into local culture, savor exquisite cuisines with our expert guides. Your
                    dream vacation awaits... start your journey with us today.
                </p>
            </div>
            <div class="images">
                <img class="elephant" src="Images/Elephant.jpg" alt="Elephant"/>
                <img class="sigiriya" src="Images/Sigiriya.jpeg" alt="Sigiriya"/>
                <img class="kandy" src="Images/kandy.jpeg" alt="Kandy"/>
                <img class="ella" src="Images/Ella.jpg" alt="Ella"/>
            </div>
            
        </div> 
        <p id="p2">Escape to a world of wonder with our travel experiences tailored just for you.
            Whether you seek the thrill of adventure, the serenity of pristine beaches, or the
            charm of historic cities, our curated itineraries promise unforgettable memories. Dive into local culture, savor exquisite cuisines with our expert guides. Your
            dream vacation awaits... start your journey with us today.
        </p>
    </section>

    <!--About section start-->
    <!-- <section class="about">
        <div class="main">
            <img src="Images/Lotus Tower.jpg" alt="Lotus Tower">
            <div class="about-text">
                <p id="p12">Why choose us?</p>
                <p id="p13"><b>Plan Your Trip With Us</b></p>
                <div class="sub">
                    <div class="sub1">
                        <img src="Images/webdevelopment.png" alt="Service">
                        <p id="p14"><b>Best Price Guarantee</b></p>
                        <p id="p15">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        <img src="Images/webdevelopment.png" alt="Service">
                        <p id="p14"><b>Best Price Guarantee</b></p>
                        <p id="p15">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        <img src="Images/webdevelopment.png" alt="Service">
                        <p id="p14"><b>Best Price Guarantee</b></p>
                        <p id="p15">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                    </div>
                </div>
            </div>        
        </div>        
    </section> -->

    <script src="script.js"></script>
</body>
</html>
