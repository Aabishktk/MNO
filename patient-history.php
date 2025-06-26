<?php
session_start();
include 'db.php';
?>

<link rel="stylesheet" href="dashboard-styles.css">
<style>
body {
    background-image: url('images/background.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    font-family: Arial, sans-serif;
}
.history-container {
    max-width: 1100px;
    margin: 40px auto;
    background-color: rgba(255, 255, 255, 0.90);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
.history-container h2 {
    color: #00796b;
    text-align: center;
    margin-bottom: 25px;
}
.filter-section {
    margin-bottom: 20px;
    text-align: center;
}
.filter-section input {
    padding: 8px 10px;
    width: 300px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    overflow: hidden;
}
th, td {
    border: 1px solid #ccc;
    padding: 12px;
    text-align: left;
}
th {
    background-color: #e0f7fa;
    color: #00695c;
    font-weight: bold;
}
tr:hover {
    background-color: #f1fdfd;
}

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

<div class="history-container">
    <h2>All Patient Medical Histories</h2>

    <!-- Button to Go to Dashboard -->
    <div class="button-container" style="text-align: center;">
        <a href="dashboard.php"><button class="go-to-dashboard-btn">Go to Dashboard</button></a>
    </div>

    <div class="filter-section">
        <input type="text" id="searchInput" placeholder="Search by patient email or doctor...">
    </div>

    <table id="historyTable">
        <thead>
            <tr>
                <th>Patient Email</th>
                <th>Diagnosis</th>
                <th>Test Results</th>
                <th>Updated By</th>
                <th>Modified At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $records = $conn->query("SELECT * FROM medical_records ORDER BY modified_at DESC");
            while ($rec = $records->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($rec['patient_email']) . "</td>";
                echo "<td>" . nl2br(htmlspecialchars($rec['diagnosis'])) . "</td>";
                echo "<td>" . nl2br(htmlspecialchars($rec['test_results'])) . "</td>";
                echo "<td>" . htmlspecialchars($rec['last_modified_by']) . "</td>";
                echo "<td>" . htmlspecialchars($rec['modified_at']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll("#historyTable tbody tr");
    rows.forEach(function (row) {
        var email = row.cells[0].textContent.toLowerCase();
        var doctor = row.cells[3].textContent.toLowerCase();
        if (email.includes(filter) || doctor.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
