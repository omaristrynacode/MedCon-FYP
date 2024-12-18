<?php
include "../admin/conn.php"; // Include your database connection file

// Query to fetch specialties
$query = "SELECT DISTINCT specialty FROM doctor"; // Adjust the query as needed
$result = $mysqli->query($query);

$specialties = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $specialties[] = $row['specialty']; // Collect specialties in an array
    }
}

// Return specialties as JSON
header('Content-Type: application/json');
echo json_encode($specialties);
?>
