<?php
// archive_doctor.php

require './conn.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dr_id = $_POST['nurse_id']; // Get doctor ID from POST request
    $status = $_POST['status']; // Get new status from POST request

    // Update the doctor's status
    $updateQuery = "UPDATE nurse SET status = ? WHERE nurse_id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("si", $status, $dr_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed."]);
    }
}
?>
