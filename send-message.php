<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    if (!empty($sender) && !empty($receiver) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_email, receiver_email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $sender, $receiver, $message);
        $stmt->execute();
        echo "Message sent.";
    } else {
        echo "Missing fields.";
    }
}
?>
