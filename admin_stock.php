<?php 
$host = 'localhost';
$user = 'root';
$pass = ''; // Update if needed
$dbname = 'mno'; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle price update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_price'])) {
    $item_id = $_POST['item_id'];
    $price = $_POST['price'];

    // Check if the price is greater than or equal to 0
    if ($price >= 0) {
        $stmt = $conn->prepare("UPDATE invoice_items SET amount = ? WHERE item_id = ?");
        $stmt->bind_param("di", $price, $item_id);
        $stmt->execute();
        $stmt->close();
        $message = "Price updated successfully!";
    } else {
        $message = "Price cannot be less than 0!";
    }
}

$result = $conn->query("SELECT item_id, item_name, quantity, amount FROM invoice_items");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/background.jpg'); /* Use relative path */
            background-size: cover;
            background-position: center;
            color: black; /* Default text color */
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background for readability */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            height: 90%; /* Allow the container to expand vertically */
            overflow-y: auto;
        }
        h2 {
            margin-bottom: 20px;
            color: teal; /* Set the Manage Stock heading to teal */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            table-layout: fixed; /* Ensures that columns remain more horizontal */
            background-color: white; /* Set the table background to white */
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: teal;
            color: white; /* Keep table headers white */
        }
        td a {
            color: teal; /* Order column text should be teal */
            text-decoration: none;
            font-weight: bold;
        }
        td a:hover {
            color: darkcyan;
        }
        input[type=number] {
            width: 80%;
            padding: 8px;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: teal;
            color: white; /* Button text should be white */
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: darkcyan;
        }
        .message {
            padding: 10px;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .navigation {
            margin-bottom: 20px;
        }
        .low-stock {
            background-color: red;
            color: white; /* Text color in the low-stock box should be white */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="navigation">
        <a href="admin-dashboard.php" style="font-size: 18px; color: teal;">Back to Dashboard</a>
    </div>

    <h2>Manage Stock</h2>

    <?php if (isset($message)): ?>
        <div class="message <?= isset($success) ? 'success' : 'error' ?>"><?= $message ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Current Quantity</th>
                <th>Update Price</th>
                <th>Order Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['item_name'] ?></td>
                    <td><?= $row['amount'] ?></td>
                    <td class="<?= $row['quantity'] < 20 ? 'low-stock' : '' ?>"><?= $row['quantity'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="number" name="price" min="0" required>
                            <input type="hidden" name="item_id" value="<?= $row['item_id'] ?>">
                            <button type="submit" name="update_price">Update Price</button>
                        </form>
                    </td>
                    <td>
                        <a href="order_stock.php?item_id=<?= $row['item_id'] ?>">Order</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
