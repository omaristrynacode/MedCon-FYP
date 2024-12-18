
<?php
// Database connection setup
include './conn.php'; // Make sure this includes your DB connection code

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['patID'])) {
    $nurse_id = $data['patID'];
    $fname = $data['patientFname'];
    $lname = $data['patientLname'];
    $phone = $data['patientPhone'];
    $email = $data['patientEmail'];
    $dob = $data["patientDOB"];
    $addy= $data["patientAddy"];

    // Prepare the SQL statement for updating nurse details
    $stmt = $mysqli->prepare("UPDATE patient SET fname=?, lname=?, phone=?, email=?, address=?, dob=? WHERE patient_id=?");
    $stmt->bind_param("ssssssi", $fname, $lname, $phone, $email,$addy, $dob, $nurse_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Patient ID not provided.']);
}

$mysqli->close();
?>
