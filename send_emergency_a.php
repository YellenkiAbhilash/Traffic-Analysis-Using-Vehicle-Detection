<?php
@include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $message = mysqli_real_escape_string($conn, $data->message);

    // Save emergency alert to the database
    $query = "INSERT INTO emergency_alerts_a (message, created_at) VALUES ('$message', NOW())";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
