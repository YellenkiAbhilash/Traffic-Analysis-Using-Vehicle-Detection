<?php
@include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $message = mysqli_real_escape_string($conn, $data->message);

    // Save alert to the database (assume you have an alerts table)
    $query = "INSERT INTO alerts_a (message, region, created_at) VALUES ('$message', 'A', NOW())";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
