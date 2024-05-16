<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mamakabi";

$motor_status = isset($_POST['motor_status']) ? intval($_POST['motor_status']) : null;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO motor_log (motor_status) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $motor_status);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
