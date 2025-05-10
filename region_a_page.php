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
    <title>Traffic Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard">
        <h1>Traffic Dashboard for A</h1>


        <h1>Vehicle Detection Stream for Region A</h1>
    <img src="http://localhost:5000/video_feed_a" width="1020" height="500" alt="Region A Vehicle Detection Stream">

    
        <div class="alert-box">
            <h2>Send Alert to Traffic Police</h2>
            <textarea id="alert-message" placeholder="Enter alert message (optional)"></textarea><br>
            <button id="send-alert">Send Alert</button>
        </div>

        <h2>Emergency Alerts from Traffic Police</h2>
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

        <h2>Alerts Sent</h2>
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
    </div>

    <script>
        // Send alert to traffic police
        document.getElementById('send-alert').addEventListener('click', () => {
            const alertMessage = document.getElementById('alert-message').value;

            fetch('send_alert_a.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: alertMessage || 'No alert message provided' })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Alert sent to Traffic Police!');
                      location.reload(); // Reload to show the new alert
                  } else {
                      alert('Failed to send alert.');
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

        // Fetch emergency alerts from traffic police
        function fetchEmergencyAlerts() {
            fetch('get_emergency_alerts_a.php')
                .then(response => response.json())
                .then(data => {
                    const alertsList = document.getElementById('emergency-alerts-list');
                    alertsList.innerHTML = '';
                    data.alerts.forEach(alert => {
                        const li = document.createElement('li');
                        li.textContent = alert.message;
                        alertsList.appendChild(li);
                    });
                });
        }
        setInterval(fetchEmergencyAlerts, 5000); // Refresh alerts every 5 seconds
    </script>
    <a href="logout.php" class="btn">Logout</a>
</body>
</html>
