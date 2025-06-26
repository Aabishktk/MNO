<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Prescription</title>
    <link rel="stylesheet" href="dashboard-styles.css">
    <style>
        body {
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            width: 700px;
            margin: 50px auto;
            background-color: rgba(255,255,255,0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #00796b;
        }
        label, select, input, button {
            display: block;
            width: 100%;
            margin: 15px 0;
            font-size: 16px;
        }

        /* Save button with #2eb3b6 color */
        .save-btn {
            background-color: #2eb3b6;
            color: white;
            font-weight: bold;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
        }

        .save-btn:hover {
            background-color: #259ca0;
        }

        .dashboard-btn {
            background-color: #2eb3b6;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-top: 15px;
        }

        .dashboard-btn:hover {
            background-color: #259ca0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Generate Prescription & Invoice</h2>
    <form method="POST" action="save-invoice.php">
        <!-- Patient dropdown -->
        <label for="patient">Select Patient:</label>
        <select name="patient_email" required>
            <option value="">-- Select Patient --</option>
            <?php
            $getPatients = $conn->query("SELECT DISTINCT email2 FROM users WHERE role='patient'");
            while ($p = $getPatients->fetch_assoc()) {
                echo "<option value='{$p['email2']}'>{$p['email2']}</option>";
            }
            ?>
        </select>

        <!-- Item dropdown -->
        <label for="item">Select Item:</label>
        <select id="itemSelect" name="item_id" required onchange="updateAmount()">
            <option value="">-- Select Item --</option>
            <?php
            $getItems = $conn->query("SELECT * FROM invoice_items");
            while ($i = $getItems->fetch_assoc()) {
                echo "<option value='{$i['item_id']}' data-price='{$i['amount']}'>{$i['item_name']} - Rs {$i['amount']}</option>";
            }
            ?>
        </select>

        <!-- Quantity -->
        <label for="quantity">Quantity:</label>
        <input type="number" id="qty" name="quantity" value="1" min="1" required oninput="updateAmount()">

        <!-- Auto total -->
        <label for="total">Total Payment:</label>
        <input type="text" id="total" name="total_payment" readonly>

        <!-- Save button -->
        <button type="submit" class="save-btn">Save Prescription & Generate Bill</button>

        <!-- Go to Dashboard Button -->
        <a href="doctor-dashboard.php" class="dashboard-btn">Go to Dashboard</a>
    </form>
</div>

<script>
    function updateAmount() {
        const item = document.getElementById("itemSelect");
        const qty = document.getElementById("qty").value;
        const price = item.options[item.selectedIndex]?.getAttribute('data-price') || 0;
        document.getElementById("total").value = (qty * price).toFixed(2);
    }
</script>
</body>
</html>
