<?php
// Database connection setup
include './conn.php'; // Make sure this includes your DB connection code

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['dr_id'])) {
    $dr_id = $data['dr_id'];
    $fname = $data['fname'];
    $lname = $data['lname'];
    $phone = $data['phone'];
    $email = $data['email'];
    $specialty = $data['specialty'];

    // Prepare the SQL statement
    $stmt = $mysqli->prepare("UPDATE doctor SET fname=?, lname=?, phone=?, email=?, specialty=? WHERE dr_id=?");
    $stmt->bind_param("sssssi", $fname, $lname, $phone, $email, $specialty, $dr_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Doctor ID not provided.']);
}

$mysqli->close();
?>
