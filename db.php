<?php
$conn = new mysqli("localhost", "root", "", "mno");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
