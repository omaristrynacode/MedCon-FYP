<?php
// Database connection setup
include './conn.php';
sleep(1); // Simulate delay for testing

// Check if 'paid_status' parameter is set in the GET request
$paid_status = isset($_GET['paid_status']) ? $_GET['paid_status'] : null;

if ($paid_status && in_array($paid_status, ['free', 'paid'])) {
    // Prepare query to filter patients by 'paid' or 'free'
    $query = "SELECT * FROM patient WHERE paid = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $paid_status); // Bind the 'paid' or 'free' value
} else {
    // If no status or 'all' is specified, fetch all patients
    $query = "SELECT * FROM patient";
    $stmt = $mysqli->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($patients);

$stmt->close();
$mysqli->close();
?>
