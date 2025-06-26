<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoiceId = $_POST['invoice_id'];

    // Simulated payment processing success
    $sql = "UPDATE invoices SET status = 1 WHERE invoice_id = $invoiceId";
    if ($conn->query($sql) === TRUE) {
        header("Location: patient-payment.php?success=1");
    } else {
        echo "Error updating payment.";
    }
}
?>
