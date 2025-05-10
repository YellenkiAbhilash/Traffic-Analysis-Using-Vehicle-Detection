<?php
@include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $id = intval($data->id);

    // Delete alert from the database
    $query = "DELETE FROM alerts_a WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
