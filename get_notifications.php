<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mno";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$doctor_id = $_GET['doctor_id'];

$query = "SELECT * FROM notifications WHERE doctor_id = '$doctor_id' AND status = 'unread' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>
