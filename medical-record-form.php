<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_email = $_POST['patient_email'];
    $diagnosis = $_POST['diagnosis'];
    $test_results = $_POST['test_results'];
    $doctor_email = $_SESSION['email'];

    $check = $conn->prepare("SELECT id FROM medical_records WHERE patient_email = ?");
    $check->bind_param("s", $patient_email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE medical_records SET diagnosis=?, test_results=?, last_modified_by=? WHERE patient_email=?");
        $stmt->bind_param("ssss", $diagnosis, $test_results, $doctor_email, $patient_email);
    } else {
        $stmt = $conn->prepare("INSERT INTO medical_records (patient_email, diagnosis, test_results, last_modified_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $patient_email, $diagnosis, $test_results, $doctor_email);
    }

    if ($stmt->execute()) {
        $successMessage = "✅ Medical record saved!";
    } else {
        $errorMessage = "❌ Error: " . $conn->error;
    }
}
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
.record-form-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.record-form-container h2 {
    color: #00796b;
    text-align: center;
    margin-bottom: 20px;
}
.record-form-container label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
    color: #004d40;
}
.record-form-container select,
.record-form-container textarea,
.record-form-container input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.record-form-container button {
    margin-top: 20px;
    background-color: #2eb3b6; /* Updated color to match Go to Dashboard button */
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}
.record-form-container button:hover {
    background-color: #1b8b8a; /* Darker shade for hover effect */
}
.success-message {
    color: green;
    font-weight: bold;
}
.error-message {
    color: red;
    font-weight: bold;
}
</style>

<div class="record-form-container">
    <h2>Add or Update Medical Record</h2>

    <?php
    if (isset($successMessage)) echo "<p class='success-message'>$successMessage</p>";
    if (isset($errorMessage)) echo "<p class='error-message'>$errorMessage</p>";
    ?>

    <form method="POST">
        <label>Patient:</label>
        <select name="patient_email" required>
            <option value="">-- Select Patient --</option>
            <?php
            $getPatients = $conn->query("SELECT DISTINCT email2 FROM users WHERE role='patient'");
            while ($p = $getPatients->fetch_assoc()) {
                echo "<option value='" . $p['email2'] . "'>" . $p['email2'] . "</option>";
            }
            ?>
        </select>

        <label>Diagnosis:</label>
        <textarea name="diagnosis" rows="4" required></textarea>

        <label>Test Results:</label>
        <textarea name="test_results" rows="4" required></textarea>

        <button type="submit">Save Record</button>
    </form>

    <!-- Go to Dashboard Button -->
    <a href="doctor-dashboard.php">
        <button style="background-color: #2eb3b6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 20px;">Go to Dashboard</button>
    </a>
</div>
