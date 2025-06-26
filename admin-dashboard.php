<?php
session_start();

// Strict admin check
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MNO</title>
    <link rel="stylesheet" href="dashboard-styles.css">
    <style>
        .sidebar {
            background-color: #2eb3b6; /* Purple for admin */
        }
        .header-buttons button {
            background-color: #8e44ad;
        }
        .header-buttons button:hover {
            background-color: #6c3483;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 5px;
            width: 30%;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 0 10px;
        }
        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Admin Sidebar -->
        <div class="sidebar">
            <h2>MNO Admin</h2>
            <ul>
                <li><a href="about-us.php">About Us</a></li>
                <li><a href="admin_stock.php">Manage Stock</a></li>
                <li><a href="add_stock.php">Add Stock</a></li>
                <li><a href="admin-invoice-update.php">Manage Invoices</a></li>
                <li><a href="logout.php">Sign Out</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Welcome, Admin <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
                <p>System Administration Panel</p>
                <div class="header-buttons">
                    <button>Generate Report</button>
                    <button>System Health</button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats-container">
                <div class="stat-card">
                    <h4>Total Users</h4>
                    <p>1,248</p>
                    <small>+12 this week</small>
                </div>
                <div class="stat-card">
                    <h4>Active Doctors</h4>
                    <p>87</p>
                    <small>3 pending approval</small>
                </div>
                <div class="stat-card">
                    <h4>Appointments Today</h4>
                    <p>156</p>
                    <small>24 completed</small>
                </div>
            </div>

            <!-- Admin Sections -->
            <div class="feature-section">
                <h3>System Management</h3>
                <ul>
                    <li>Add/Remove Users</li>
                    <li>Manage Permissions</li>
                    <li>View Audit Logs</li>
                    <li>System Configuration</li>
                    <li>Database Backup</li>
                </ul>
            </div>

            <div class="schedule-section">
                <h3>Recent Activity</h3>
                <ul>
                    <li><strong>Today</strong> - 5 new users registered</li>
                    <li><strong>Today</strong> - 12 appointments scheduled</li>
                    <li><strong>Today</strong> - 3 doctor approvals pending</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>