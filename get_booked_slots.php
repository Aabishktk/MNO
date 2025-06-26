<?php
$conn = new mysqli("localhost", "root", "", "mno");

$doctor_id = $_POST['doctor_id'];
$date = $_POST['date'];

$result = $conn->query("SELECT time FROM appointments WHERE doctor_id = '$doctor_id' AND date = '$date'");
$bookedSlots = [];

while ($row = $result->fetch_assoc()) {
    $bookedSlots[] = $row['time'];
}

echo json_encode($bookedSlots);
?>
