<?php
@include 'config.php';

$query = "SELECT * FROM alerts_b WHERE region = 'B' ORDER BY created_at DESC"; // Ensure you have a created_at timestamp
$result = mysqli_query($conn, $query);
$alerts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $alerts[] = $row;
}

echo json_encode(['alerts' => $alerts]);
?>
