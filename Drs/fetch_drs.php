<?php
// Database connection setup
include '../admin/conn.php';
sleep(1); // Simulate delay for testing

// Fetch all doctors from the database
$status = isset($_GET['status']) ? $_GET['status'] : null;

if ($status) {
    // Fetch doctors with the specified status
    $query = "SELECT * FROM doctor WHERE status = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $status); // "s" means the parameter is a string
} else {
    // Fetch all doctors if no status is specified
    $query = "SELECT * FROM doctor";
    $stmt = $mysqli->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($doctors);
?>
