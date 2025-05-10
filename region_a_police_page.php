<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Traffic Police Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Traffic Police Dashboard For A</h1>


    <section id="emergency-response">
        <h2>Emergency Response</h2>
        <textarea id="emergency-message" placeholder="Enter emergency message (optional)"></textarea><br>
        <button id="send-emergency">Send Emergency Alert</button>
    </section>

    <h2>Received Alerts</h2>
    <ul id="alerts-list">
        <?php
        $query = "SELECT * FROM alerts_a WHERE region = 'A' ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . htmlspecialchars($row['message']) . 
                 ' <button onclick="deleteAlert(' . $row['id'] . ')">Delete</button></li>';
        }
        ?>
    </ul>

    <h2>Emergency Alerts Sent</h2>
    <ul id="emergency-alerts-list">
        <?php
        $query = "SELECT * FROM emergency_alerts_a ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . htmlspecialchars($row['message']) . 
                 ' <button onclick="deleteEmergency(' . $row['id'] . ')">Delete</button></li>';
        }
        ?>
    </ul>

    <script>
        // Fetch alerts from traffic authority
        function fetchTrafficAlerts() {
            fetch('get_traffic_alerts_a.php')
                .then(response => response.json())
                .then(data => {
                    const alertsList = document.getElementById('traffic-alerts-list');
                    alertsList.innerHTML = '';
                    data.alerts.forEach(alert => {
                        const li = document.createElement('li');
                        li.textContent = alert.message;
                        alertsList.appendChild(li);
                    });
                });
        }
        setInterval(fetchTrafficAlerts, 5000); // Refresh alerts every 5 seconds

        // Send emergency alert to traffic authority
        document.getElementById('send-emergency').addEventListener('click', () => {
            const emergencyMessage = document.getElementById('emergency-message').value;

            fetch('send_emergency_a.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: emergencyMessage || 'No message provided' })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Emergency alert sent to Traffic Authority!');
                  } else {
                      alert('Failed to send emergency alert.');
                  }
              });
        });

        // Delete alert function
        function deleteAlert(id) {
            fetch('delete_alert_a.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Alert deleted successfully!');
                      location.reload(); // Reload to update the list
                  } else {
                      alert('Failed to delete alert.');
                  }
              });
        }

        // Delete emergency function
        function deleteEmergency(id) {
            fetch('delete_emergency_a.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Emergency alert deleted successfully!');
                      location.reload(); // Reload to update the list
                  } else {
                      alert('Failed to delete emergency alert.');
                  }
              });
        }
    </script>
    <a href="logout.php" class="btn">Logout</a>
</body>
</html>
