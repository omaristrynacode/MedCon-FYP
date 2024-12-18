<?php
include "../admin/conn.php";

// Get the updated values from the AJAX request
$appt_id = $_POST['appt_id'];
$patient_id = $_POST['patient_id'];
$date = $_POST['date'];
$time = $_POST['time'];
$reason = $_POST['reason'];
$hospital = $_POST['hospital'];

// Update the appointment in the database
$sql = "UPDATE appointment SET date = '$date', time = '$time', reason = '$reason' WHERE appointment_id = '$appt_id'";

if ($mysqli->query($sql) === TRUE) {
    echo "Appointment updated successfully";
} else {
    echo "Error updating appointment: " . $mysqli->error;
}


?>