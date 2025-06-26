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

// Handle stock order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_stock'])) {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Validate quantity
    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE invoice_items SET quantity = quantity + ? WHERE item_id = ?");
        $stmt->bind_param("ii", $quantity, $item_id);
        $stmt->execute();
        $stmt->close();
        $message = "Stock ordered successfully!";
    } else {
        $message = "Quantity cannot be 0 or below!";
    }
}

// Get all invoice items for the dropdown
$result = $conn->query("SELECT item_id, item_name FROM invoice_items");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/background.jpg'); /* Use relative path */
            background-size: cover;
            background-position: center;
            color: white;
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
            background-color: rgba(255, 255, 255, 0.7); /* Transparent white background */
            color: black; /* Text color set to black */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        h2 {
            margin-bottom: 20px;
            color: teal; /* Teal color for heading */
        }
        select, input[type=number] {
            padding: 8px;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 80%;
        }
        button {
            padding: 10px 20px;
            background-color: white;
            color: teal;
            border: 1px solid teal;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: teal;
            color: white;
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
        a {
            color: teal;
            font-weight: bold;
            text-decoration: none;
        }
        a:hover {
            color: darkcyan;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Stock</h2>

    <?php if (isset($message)): ?>
        <div class="message <?= isset($success) ? 'success' : 'error' ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <select name="item_id" required>
            <option value="">Select Item</option>
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['item_id'] ?>"><?= $row['item_name'] ?></option>
            <?php endwhile; ?>
        </select>
        
        <input type="number" name="quantity" min="1" required placeholder="Enter quantity to order">
        
        <button type="submit" name="order_stock">Order Stock</button>
    </form>

    <?php if (isset($message)): ?>
        <div class="message success">
            <p><a href="admin_stock.php">Go back to Manage Stock</a></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
