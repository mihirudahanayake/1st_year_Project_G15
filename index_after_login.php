<?php
include('session_check.php'); // Ensure user is logged in and authorized

// Your profile code goes here

include('config.php');
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="index_after_login.css">
</head>
<body>
    <header class="header">
        <nav>
            <div class="navbar">
                <button class="menu-btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </button>
                <h3 class="logo">
                    <span>
                        <span class="t-span1">Travel</span>
                        <span class="t-span2">Mate</span>
                    </span>
                </h3>
                <ul id="nav-menu">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="room_list.php">Hotels</a></li>
                    <li><a href="travel_destination.php">Travel Destinations</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
                <button id="profile" onclick="location.href='profile.php'"><b>View Profile</b></button>
            </div>
        </nav>
    </header>

    <!-- Side Navigation -->
    <div class="side-nav" id="side-nav">
        <button class="close-btn" id="close-btn">&times;</button>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="room_list.php">Hotels</a></li>
            <li><a href="travel_destination.php">Travel Destinations</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </div>

    <!-- Home section start -->
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

    <script src="script.js"></script>
</body>
</html>
