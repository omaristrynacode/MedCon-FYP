<?php 

include './conn.php';
$query = "SELECT * FROM hospital";
$stmt = $mysqli->prepare($query);
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