<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mno";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume doctor is logged in (Replace with real session-based authentication)
$doctor_id = 1; // Change this to the logged-in doctor's ID

// Fetch appointments for this doctor
$appointments = $conn->query("SELECT * FROM appointments WHERE doctor_id = '$doctor_id' ORDER BY date DESC");

// Fetch unread notifications for the doctor
$notifications = $conn->query("SELECT * FROM notifications WHERE doctor_id = '$doctor_id' AND status = 'unread' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="style.css?v=1"> <!-- Force cache refresh -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-content {
            width: 80%;
            max-width: 1100px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #2eb3b6;
            padding: 10px 20px;
            color: white;
            border-radius: 8px;
        }

        .top-nav .icons {
            display: flex;
            gap: 20px;
        }

        .top-nav .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .go-dashboard-btn {
            text-align: center;
            margin-top: 20px;
        }

        .go-dashboard-btn a button {
            padding: 12px 30px;
            background-color: #2eb3b6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .go-dashboard-btn a button:hover {
            background-color: #1e8c8a;
        }

        .notifications-container,
        .appointments-container {
            margin-top: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2eb3b6;
        }

        .notification-box {
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .notification-box.unread {
            background-color: #e0f7fa;
        }

        .notification-box.read {
            background-color: #f1f1f1;
        }

        .notification-box p {
            margin: 0;
            font-size: 14px;
        }

        .appointments-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .appointments-container table th,
        .appointments-container table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .appointments-container table th {
            background-color: #2eb3b6;
            color: white;
        }

        .appointments-container table td {
            background-color: #ffffff;
        }

        .appointments-container table tr:hover {
            background-color: #f1f1f1;
        }

        .no-notif {
            text-align: center;
            color: #777;
            font-size: 16px;
            padding: 20px;
        }

    </style>
</head>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let notifications = document.querySelectorAll(".notification-box.unread");

    notifications.forEach(notification => {
        notification.addEventListener("click", function () {
            let notificationId = this.getAttribute("data-id");

            // Send an AJAX request to mark notification as read
            fetch("mark_as_read.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "notification_id=" + notificationId
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    notification.classList.remove("unread");
                    notification.classList.add("read");
                }
            });
        });
    });
});
</script>

<body>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Navigation Bar -->
        <div class="top-nav">
            <div class="icons">
                <img src="settings_icon.jpg" class="icon" alt="Settings">
                <img src="notification_icon.jpg" class="icon" alt="Notifications">
                <div class="profile">
                    <span>Dr. John Doe (Cardiologist)</span>
                    <img src="profile.jpg" class="profile-pic" alt="Profile Picture">
                </div>
            </div>
        </div>

        <!-- Button to go to dashboard -->
        <div class="go-dashboard-btn">
            <a href="doctor-dashboard.php">
                <button>
                    Go to Dashboard
                </button>
            </a>
        </div>

        <!-- Notifications Section -->
        <div class="notifications-container">
            <h2>Notifications</h2>
            <div class="notifications-list">
                <?php
                if ($notifications->num_rows > 0) {
                    while ($row = $notifications->fetch_assoc()) { ?>
                        <div class="notification-box unread" data-id="<?= $row['id']; ?>">
                            <p><?= htmlspecialchars($row['message']); ?></p>
                            <small><?= htmlspecialchars($row['created_at']); ?></small>
                        </div>
                    <?php }
                } else {
                    echo "<div class='notification-box no-notif'>No new notifications.</div>";
                }
                ?>
            </div>
        </div>

        <!-- Appointments Section -->
        <div class="appointments-container">
            <h2>My Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Message</th> <!-- New Column for Messages -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($appointments->num_rows > 0) {
                        while ($row = $appointments->fetch_assoc()) { 
                            // Format time properly
                            $appointment_time = (!empty($row['time']) && $row['time'] !== '00:00:00') 
                                ? date("h:i A", strtotime($row['date'] . " " . $row['time'])) 
                                : 'N/A';
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($row['patient_name']); ?></td>
                                <td><?= htmlspecialchars($row['date']); ?></td>
                                <td><?= $appointment_time; ?></td>
                                <td><?= htmlspecialchars($row['message']); ?></td> <!-- Display Patient Message -->
                            </tr>
                        <?php }
                    } else {
                        echo "<tr><td colspan='4' class='no-notif'>No appointments scheduled.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
