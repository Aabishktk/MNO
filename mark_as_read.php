<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mno";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if notification ID is provided
if (isset($_POST['notification_id'])) {
    $notification_id = intval($_POST['notification_id']);

    // Update notification status to "read"
    $update_query = "UPDATE notifications SET status = 'read' WHERE id = $notification_id";
    if ($conn->query($update_query) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid";
}

$conn->close();
?>
