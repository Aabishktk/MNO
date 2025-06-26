<?php
session_start(); // Start the session

// Connect to database
$conn = new mysqli("localhost", "root", "", "appointment_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch doctors for dropdown
$doctors = $conn->query("SELECT * FROM doctors");

// Check if the logged-in user is a doctor
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'doctor') {
    $doctor_id = $_SESSION['doctor_id']; // Doctor's ID

    // Fetch unread notifications for this doctor
    $notifications = [];
    $query = "SELECT * FROM notifications WHERE doctor_id = '$doctor_id' AND status = 'unread' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $notifications[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Top Navigation Bar -->
        <div class="top-nav">
            <div class="icons">
                <img src="settings_icon.jpg" class="icon">
                <img src="notification_icon.jpg" class="icon">
                <div class="profile">
                    <span>Patient_1</span>
                    <img src="profile.jpg" class="profile-pic">
                </div>
            </div>
        </div>

        <!-- Button to Go to Dashboard -->
        <div class="button-container" style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php"><button class="go-to-dashboard-btn">Go to Dashboard</button></a>
        </div>

        <!-- Show Notifications Only for Doctors -->
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'doctor') { ?>
            <div id="notifications">
                <h3>Notifications</h3>
                <ul>
                    <?php if (!empty($notifications)) {
                        foreach ($notifications as $row) { ?>
                            <li><?= htmlspecialchars($row['message']); ?> <small><?= $row['created_at']; ?></small></li>
                        <?php }
                    } else {
                        echo "<li>No notifications.</li>";
                    } ?>
                </ul>
            </div>
        <?php } ?>

        <!-- Appointment Form -->
        <div class="container">
            <div class="form-container">
                <h1>Book Your Appointment</h1>
                <form action="process.php" method="POST">

                    <div class="form-row">
                        <div>
                            <label>Name</label>
                            <input type="text" name="name" required>
                        </div>
                        <div>
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label>Phone Number</label>
                            <input type="text" name="phone" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label>Date</label>
                            <input type="date" name="date" required>
                        </div>
                        <div>
                            <label>Time</label>
                            <input type="time" name="time" required>
                        </div>
                    </div>

                    <div>
                        <label>Choose a Doctor</label>
                        <select name="doctor_id" required>
                            <option value="">Select Doctor</option>
                            <?php while ($row = $doctors->fetch_assoc()) { ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?> (<?= $row['specialization'] ?>)</option>
                            <?php } ?>
                        </select>
                    </div>

                    <label>Message</label>
                    <textarea name="message" required></textarea>

                    <button type="submit" class="book-btn">
                        <img src="pin.jpg" class="pin"> BOOK NOW
                    </button>
                </form>
            </div>

            <!-- Schedule Section -->
            <div class="schedule-container">
                <h3>Schedule</h3>
                <div class="schedule-item">
                    <div class="date">2024 December 20</div>
                    <div class="event">Surgery</div>
                </div>
                <div class="schedule-item">
                    <div class="date">2024 December 22</div>
                    <div class="event">Therapy</div>
                </div>
                <h3>Appointment</h3>
                <img src="calendar.jpg" alt="Calendar">
            </div>
        </div>

    </div>

</body>
</html>

<?php
$conn->close();
?>

<style>
/* Teal Button Style */
.go-to-dashboard-btn {
    background-color: #00796b; /* Teal color */
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin: 20px 0;
    text-align: center;
    display: inline-block;
}

.go-to-dashboard-btn:hover {
    background-color: #004d40; /* Dark teal on hover */
}
</style>
