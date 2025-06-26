<?php
session_start();

// Check if the user is logged in and is a patient
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MNO</title>
    <link rel="stylesheet" href="dashboard-styles.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <!-- Sidebar -->
<div class="sidebar">
    <h2>MNO Patient</h2>
    <ul>
        <li><a href="index1.php">Appointment</a></li> <!-- Change this link -->
                <li><a href="patient-history.php">Patients History</a></li>
                <li><a href="patient-chat.php">Message</a></li>
                <li><a href="patient-payment.php">Payment</a></li>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="logout.php">Sign Out</a></li>
    </ul>
</div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Welcome, <?php echo $_SESSION['name']; ?></h1>
                <p>YOUR HEALTH OUR PRIORITY</p>
                <div class="header-buttons">
                    <button>Get Started</button>
                    <button>Learn More</button>
                </div>
            </div>

            <!-- Rest of your existing patient dashboard content -->
            <div class="feature-section">
                <h3>Feature</h3>
                <ul>
                    <li>Hospital</li>
                    <li>Doctor</li>
                    <li>Laboratory</li>
                    <li>Pharmacy</li>
                    <li>Prescription</li>
                </ul>
            </div>

            <!-- Nearby Hospital Section -->
            <div class="nearby-hospital-section">
                <h3>Nearby Hospital</h3>
                <button>See All</button>
            </div>

            <!-- Patient Section -->
            <div class="patient-section">
                <h3><?php echo $_SESSION['name']; ?></h3>
                <p><?php echo $_SESSION['email']; ?></p>
            </div>

            <!-- Schedule Section -->
            <div class="schedule-section">
                <h3>Schedule</h3>
                <ul>
                    <li><strong>2024</strong> - December - 20 - Surgery</li>
                    <li><strong>2024</strong> - December - 22 - Therapy</li>
                </ul>
            </div>

            <!-- Appointment Section -->
            <div class="appointment-section">
                <h3>Appointment</h3>
                <ul>
                    <li><strong>Lifestyle Counseling</strong> - Dr. Claudia Alves</li>
                    <li><strong>Rehabilitation</strong> - Dr. Claudia Alves</li>
                    <li><strong>Preventive Care</strong> - Dr. Claudia Alves</li>
                </ul>
            </div>

            <!-- Message Section -->
            <div class="message-section">
                <h3>Message</h3>
                <p><strong>Dr Asim</strong> - 1 Minute Ago</p>
                <p>You automatically lose the chances you don't take.</p>
            </div>
        </div>
    </div>
</body>
</html>