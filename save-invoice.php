<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_email = $_POST['patient_email'];
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $total = (float)$_POST['total_payment'];

    // Insert invoice
    $stmt = $conn->prepare("INSERT INTO invoices (patient_email, total_payment) VALUES (?, ?)");
    $stmt->bind_param("sd", $patient_email, $total);
    if ($stmt->execute()) {
        echo "<script>alert('Prescription saved! Invoice generated successfully.'); window.location.href='doctor-dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
    $stmt->close();
}
?>
