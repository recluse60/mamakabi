<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Istanbul');

// Include database connection
include('database.php');

// Function to log user connection
function logUserConnection($userId, $ipAddress) {
    global $conn;

    // Get current date and time
    $dateTime = date('Y-m-d H:i:s');

    // Insert log data into the database
    $stmt = $conn->prepare("INSERT INTO logs (musteri_id, connection_time, ip_address) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $dateTime, $ipAddress]);
}

// Example usage:
// Assuming $_SESSION["user_id"] holds the user's ID after login
if(isset($_SESSION["musteri_id"])) {
    $userId = $_SESSION["musteri_id"];
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address
    logUserConnection($userId, $ipAddress);
}
?>