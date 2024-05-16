<?php
// Include database connection
include('database.php');
// Include connection log script
include('connection_log.php');

// Sample user ID and IP address
$userId = 27; // Replace with an actual user ID
$ipAddress = '127.0.0.1'; // Replace with an actual IP address

// Call the logUserConnection() function with sample data
logUserConnection($userId, $ipAddress);

echo "Connection logged successfully!";
?>
