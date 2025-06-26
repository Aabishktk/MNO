<?php 
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Patient Invoices</title>
    <link rel="stylesheet" href="dashboard-styles.css">
    <style>
        body {
            font-family: Arial;
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            width: 85%;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.97);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            padding: 14px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #2eb3b6;  /* Updated color */
            color: white;
        }
        .status-paid {
            color: green;
            font-weight: bold;
        }
        .status-unpaid {
            color: red;
            font-weight: bold;
        }
        .dashboard-button {
            display: block;
            width: 100%;
            background-color: #2eb3b6;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        .dashboard-button:hover {
            background-color: #1b8b8a; /* Darker shade for hover */
        }
    </style>
</head>
<body>
<div class="container">
    <h2 style="text-align:center;">All Patient Invoices</h2>

    <table>
        <tr>
            <th>Invoice ID</th>
            <th>Patient Email</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM invoices ORDER BY invoice_id DESC");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['invoice_id']}</td>
                    <td>{$row['patient_email']}</td>
                    <td>Rs {$row['total_payment']}</td>
                    <td class='" . ($row['status'] ? "status-paid" : "status-unpaid") . "'>" . 
                        ($row['status'] ? "Paid" : "Unpaid") . "</td>
                  </tr>";
        }
        ?>
    </table>

    <!-- Go to Dashboard Button -->
    <a href="admin-dashboard.php">
        <button class="dashboard-button">Go to Dashboard</button>
    </a>
</div>
</body>
</html>
