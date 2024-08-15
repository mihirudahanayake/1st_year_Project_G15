<?php
session_start();
include('config.php');

// Check if the user is logged in and is a hotel admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'hotel_admin') {
    header("Location: login.html");
    exit();
}

// Mark notification as read
if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: hotel_dashboard.php");
exit();
?>
