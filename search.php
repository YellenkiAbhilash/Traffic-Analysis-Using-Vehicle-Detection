<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $region = trim($_POST['region']); // Get the region input and trim whitespace
    $region_lower = strtolower($region); // Convert to lowercase to handle case insensitivity

    // Check if the input matches "region a" (ignoring spaces)
    if (str_replace(' ', '', $region_lower) === 'regiona') {
        // Redirect to Region A stream
        header('Location: region_a_page.php');
        exit();
    } elseif (str_replace(' ', '', $region_lower) === 'regionb') {
        // Redirect to Region B stream
        header('Location: region_b_page.php');
        exit();
    } else {
        echo "Region not found. Please enter a valid region.";
    }
}
?>
