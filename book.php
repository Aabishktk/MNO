<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mno");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$date = $_POST['date'];
$time = $_POST['time']; // This is in HH:MM format from input[type=time]
$doctor_id = $_POST['doctor_id'];
$message = $_POST['message'];

// Ensure time is stored in HH:MM:SS format
$timeFormatted = date("H:i:s", strtotime($time));

// Use prepared statement for inserting into database
$stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, date, time, doctor_id, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssis", $name, $email, $phone, $date, $timeFormatted, $doctor_id, $message);

if ($stmt->execute()) {
    echo "Appointment booked successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
