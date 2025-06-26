<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example credentials (In real use, fetch from database)
    $correct_email = "user@example.com";
    $correct_password = "password123"; // This should be hashed in a real application

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password match the stored ones
    if ($email === $correct_email && $password === $correct_password) {
        // Successful login - Redirect to the dashboard or home page
        header('Location: dashboard.php');
        exit();
    } else {
        // Incorrect credentials - Set an error message and redirect back to login page
        $_SESSION['login_error'] = "Incorrect email or password. Please try again.";
        $_SESSION['login_email'] = $email; // Preserve email for repopulating the field
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MNO</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>LOGIN TO YOUR ACCOUNT</h2>
            <p>Welcome back! Please enter your credentials to access your account.</p>
            
            <!-- PHP error message -->
            <?php 
                if (isset($_SESSION['login_error'])) {
                    echo '<div class="error-message">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
                    unset($_SESSION['login_error']);
                }
            ?>
        </div>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required
                       value="<?php 
                          echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; 
                          unset($_SESSION['login_email']);
                       ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">LOGIN</button>
        </form>
        
        <div class="signup-link">
            <p>Don't have an account? <a href="signup.html">Sign Up now</a></p>
        </div>
        
        <div class="additional-info">
            <p><strong>Phone:</strong> 8888-0000</p>
            <p><strong>Website:</strong> www.eco.com</p>
            <p><strong>Email:</strong> info@eco.com</p>
            <p><strong>Address:</strong> Eliy University, Inc.</p>
        </div>
    </div>
</body>
</html>
