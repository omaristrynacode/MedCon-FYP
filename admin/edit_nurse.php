<?php
// Database connection setup
include './conn.php'; // Make sure this includes your DB connection code

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['nurse_id'])) {
    $nurse_id = $data['nurse_id'];
    $fname = $data['fname'];
    $lname = $data['lname'];
    $phone = $data['phone'];
    $email = $data['email'];
    $assigned_dr = $data["assigned_dr"];
    $hosp = $data["hosp_id"];

    // Prepare the SQL statement for updating nurse details
    $stmt = $mysqli->prepare("UPDATE nurse SET fname=?, lname=?, phone=?, email=?, assigned_dr=?, hosp_id=? WHERE nurse_id=?");
    $stmt->bind_param("ssssssi", $fname, $lname, $phone, $email,$assigned_dr, $hosp, $nurse_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Nurse ID not provided.']);
}

$mysqli->close();
?>
