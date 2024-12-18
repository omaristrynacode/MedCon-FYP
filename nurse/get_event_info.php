<?php
include "../admin/conn.php";

// Get the date from the AJAX request
$date = $_POST['date'];

// Sanitize the input
$date = $mysqli->real_escape_string($date);

// Retrieve the event information from the database
$sql = "SELECT * FROM appointment WHERE appointment_id = '$date'";
$result = $mysqli->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
    // Get the event information
    $eventInfo = $result->fetch_assoc();
    $patient = $eventInfo["patient_id"];
    $sql1 = "SELECT * FROM patient WHERE patient_id = '$patient'";
    $result1 = $mysqli->query($sql1);
    $patientInfo = $result1->fetch_assoc();
    $hosp = $eventInfo["hosp_id"];
    $sql3 = "SELECT * FROM hospital WHERE Hospital_ID = '$hosp'";
    $result3 = $mysqli->query($sql3);
    $hospInfo = $result3->fetch_assoc();
    // Combine the event and patient information
    $response = array_merge($eventInfo, $patientInfo, $hospInfo);
    // Send the event information back to the client
    echo json_encode($response);
} else {
    // Send an empty object back to the client
    echo json_encode(array());
}

// Close the database connection
$mysqli->close();
?>
