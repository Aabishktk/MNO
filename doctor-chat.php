<?php
session_start();
include 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit();
}
$doctorEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Chat</title>
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
            display: flex;
            flex-direction: column;
        }
        .chat-container h2 {
            color: #00695c;
            text-align: center;
            margin-bottom: 20px;
        }
        select, textarea, button {
            padding: 12px;
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
        }
        button:hover {
            background: #259ca0;
        }

        /* Go to Dashboard Button */
        .dashboard-btn {
            background-color: #2eb3b6;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: block;
            margin-top: 10px;
            width: 100%;
        }

        .dashboard-btn:hover {
            background-color: #259ca0;
        }

        /* Align the Go to Dashboard button */
        .dashboard-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        /* Horizontal form layout */
        form {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
        }

        form textarea {
            width: 75%;
        }

        form button {
            width: 20%;
            min-width: 120px;
        }

    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Message a Patient</h2>

        <div class="dashboard-container">
            <a href="doctor-dashboard.php" class="dashboard-btn">Go to Dashboard</a>
        </div>

        <label for="patient">Select Patient:</label>
        <select id="patientSelect">
            <option value="">-- Choose Patient --</option>
            <?php
            $getPatients = $conn->query("SELECT DISTINCT email2 FROM users");
            while ($p = $getPatients->fetch_assoc()) {
                echo "<option value='" . $p['email2'] . "'>" . $p['email2'] . "</option>";
            }
            ?>
        </select>

        <div id="messages" class="messages"></div>

        <form id="chatForm">
            <textarea name="message" id="message" placeholder="Enter your message..." required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>

    <script>
        const form = document.getElementById("chatForm");
        const messagesDiv = document.getElementById("messages");
        const patientSelect = document.getElementById("patientSelect");

        function loadMessages() {
            const patient = patientSelect.value;
            if (!patient) return;

            const xhr = new XMLHttpRequest();
            xhr.open("GET", `load-messages.php?doctor=<?php echo $doctorEmail; ?>&patient=${patient}`, true);
            xhr.onload = function () {
                const temp = document.createElement("div");
                temp.innerHTML = this.responseText;

                temp.querySelectorAll(".msg").forEach(function(el) {
                    if (el.innerText.startsWith("<?php echo $doctorEmail; ?>")) {
                        el.classList.add("doctor");
                    } else {
                        el.classList.add("patient");
                    }
                });

                messagesDiv.innerHTML = temp.innerHTML;
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            };
            xhr.send();
        }

        patientSelect.addEventListener("change", loadMessages);

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const patient = patientSelect.value;
            const message = document.getElementById("message").value;
            if (!patient || !message) return;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "send-message.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                document.getElementById("message").value = "";
                loadMessages();
            };
            xhr.send(`sender=<?php echo $doctorEmail; ?>&receiver=${patient}&message=${encodeURIComponent(message)}`);
        });

        setInterval(loadMessages, 5000);
    </script>
</body>
</html>
