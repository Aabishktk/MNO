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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if passwords match
    if ($password != $confirm_password) {
        $_SESSION['signup_error'] = "Passwords do not match!";
        header("Location: signup.html");
        exit();
    }

    // Determine role based on email prefix
    $role = 'patient';
    $emailPrefix = strtolower(substr($email, 0, strpos($email, '@')));
    
    if (strpos($emailPrefix, 'admin') === 0) {
        $role = 'admin';
    } elseif (strpos($emailPrefix, 'doctor') === 0) {
        $role = 'doctor';
    }

    // Insert user into database
    $sql = "INSERT INTO users (name2, email2, pass2, role) VALUES ('$name', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        // Auto-login the user
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;
        $_SESSION['loggedin'] = true;
        
        // Redirect to appropriate dashboard
        switch ($role) {
            case 'admin':
                header("Location: index.php");
                break;
            case 'doctor':
                header("Location: index.php");
                break;
            default:
                header("Location: index.php");
        }
        exit();
    } else {
        $_SESSION['signup_error'] = "Error: " . $conn->error;
        header("Location: signup.html");
        exit();
    }
}

$conn->close();
?>