<?php
session_start();

include 'db.php';

$doctor = $_GET['doctor'];
$patient = $_GET['patient'];

$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_email = ? AND receiver_email = ?) OR (sender_email = ? AND receiver_email = ?) ORDER BY sent_at ASC");
$stmt->bind_param("ssss", $doctor, $patient, $patient, $doctor);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $isDoctor = $row['sender_email'] === $doctor;
    echo '<div class="msg" style="text-align: ' . ($isDoctor ? 'right' : 'left') . ';">';
    echo '<strong>' . htmlspecialchars($row['sender_email']) . ':</strong><br>';
    echo nl2br(htmlspecialchars($row['message'])) . '<br>';
    echo '<small>' . $row['sent_at'] . '</small>';
    echo '</div>';
}
?>
