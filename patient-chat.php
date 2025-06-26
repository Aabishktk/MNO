<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'patient') {
    header("Location: login.php");
    exit();
}
$patientEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Chat</title>
    <link rel="stylesheet" href="dashboard-styles.css">
    <style>
        body {
            background: url('images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .chat-container {
            max-width: 1000px;
            margin: 40px auto;
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .chat-container h2 {
            color: #00695c;
            text-align: center;
            margin-bottom: 20px;
        }
        select {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .messages {
            height: 350px;
            overflow-y: auto;
            background: #e0f2f1;
            padding: 15px;
            margin-top: 15px;
            border-radius: 10px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
        }
        .msg {
            margin-bottom: 12px;
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 75%;
            display: inline-block;
            clear: both;
        }
        .msg.doctor {
            background-color: #80cbc4;
            color: #00332e;
            float: left;
        }
        .msg.patient {
            background-color: #c8e6c9;
            color: #2e7d32;
            float: right;
        }
        button {
            background: #2eb3b6;
            color: white;
            font-weight: bold;
            transition: background 0.3s;
            cursor: pointer;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            width: auto;
        }
        button:hover {
            background: #259ca0;
        }
        .button-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .go-to-dashboard-btn {
            background: #2eb3b6;
            color: white;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            transition: background 0.3s;
        }
        .go-to-dashboard-btn:hover {
            background: #259ca0;
        }
        form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        #message {
            flex: 1;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            height: 40px;
        }
        .send-btn-container {
            display: flex;
            align-items: flex-start;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Chat with Your Doctor</h2>

        <!-- Button to Go to Dashboard -->
        <div class="button-container">
            <a href="dashboard.php"><button class="go-to-dashboard-btn">Go to Dashboard</button></a>
        </div>

        <label for="doctor">Select Doctor:</label>
        <select id="doctorSelect">
            <option value="">-- Choose Doctor --</option>
            <?php
            $getDoctors = $conn->query("SELECT DISTINCT sender_email FROM messages WHERE receiver_email='$patientEmail'");
            while ($d = $getDoctors->fetch_assoc()) {
                echo "<option value='" . $d['sender_email'] . "'>" . $d['sender_email'] . "</option>";
            }
            ?>
        </select>

        <div id="messages" class="messages"></div>

        <!-- Chat Form (Message input and Send button side by side) -->
        <form id="chatForm">
            <div class="send-btn-container">
                <textarea name="message" id="message" placeholder="Your reply..." required></textarea>
                <button type="submit">Send Reply</button>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById("chatForm");
        const messagesDiv = document.getElementById("messages");
        const doctorSelect = document.getElementById("doctorSelect");

        function loadMessages() {
            const doctor = doctorSelect.value;
            if (!doctor) return;

            const xhr = new XMLHttpRequest();
            xhr.open("GET", `load-messages.php?doctor=${doctor}&patient=<?php echo $patientEmail; ?>`, true);
            xhr.onload = function () {
                const temp = document.createElement("div");
                temp.innerHTML = this.responseText;

                // Style each msg bubble
                temp.querySelectorAll(".msg").forEach(function(el) {
                    if (el.innerText.startsWith("<?php echo $patientEmail; ?>")) {
                        el.classList.add("patient");
                    } else {
                        el.classList.add("doctor");
                    }
                });

                messagesDiv.innerHTML = temp.innerHTML;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            };
            xhr.send();
        }

        doctorSelect.addEventListener("change", loadMessages);

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const doctor = doctorSelect.value;
            const message = document.getElementById("message").value;
            if (!doctor || !message) return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "send-message.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                document.getElementById("message").value = "";
                loadMessages();
            };
            xhr.send(`sender=<?php echo $patientEmail; ?>&receiver=${doctor}&message=${encodeURIComponent(message)}`);
        });

        setInterval(loadMessages, 5000);
    </script>
</body>
</html>
