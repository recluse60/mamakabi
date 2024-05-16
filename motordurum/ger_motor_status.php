<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mamakabi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT motordurum FROM motor WHERE id = 1");
$row = $result->fetch_assoc();
echo $row['motordurum'];

$conn->close();
?>
