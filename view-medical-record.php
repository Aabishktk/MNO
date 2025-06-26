<?php
include 'db.php';
$patient_email = $_GET['email'] ?? '';
?>

<link rel="stylesheet" href="dashboard-styles.css">
<div class="dashboard-container">
    <h2>Medical Record Viewer</h2>
    <?php
    if ($patient_email) {
        $stmt = $conn->prepare("SELECT * FROM medical_records WHERE patient_email = ?");
        $stmt->bind_param("s", $patient_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo "<div class='record-box'><strong>Diagnosis:</strong><br>" . nl2br(htmlspecialchars($row['diagnosis'])) . "</div><br>";
            echo "<div class='record-box'><strong>Test Results:</strong><br>" . nl2br(htmlspecialchars($row['test_results'])) . "</div><br>";
            echo "<div class='record-box'><strong>Last Modified By:</strong> " . htmlspecialchars($row['last_modified_by']) . "</div><br>";
            echo "<div class='record-box'><strong>Modified At:</strong> " . htmlspecialchars($row['modified_at']) . "</div>";
        } else {
            echo "<p>No medical record found.</p>";
        }
    } else {
        echo "<p>No patient selected.</p>";
    }
    ?>
</div>