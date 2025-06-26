<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mno";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email2=? AND pass2=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Set session variables
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name2'];
        $_SESSION['loggedin'] = true;
        
        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin-dashboard.php");
                exit();
            case 'doctor':
                header("Location: doctor-dashboard.php");
                exit();
            default:
                header("Location: dashboard.php");
                exit();
        }
    } else {
        // Set error in session and redirect back
        $_SESSION['login_error'] = "Invalid email or password";
        $_SESSION['login_email'] = $email; // Remember email
        header("Location: index.html");
        exit();
    }
    
    $stmt->close();
}

$conn->close();
?>