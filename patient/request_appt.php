<?php
session_start();
include "../admin/conn.php";

// Check if the user is logged in
if (!isset($_SESSION['sess_user'])) {
    header("Location: login.php");
    exit();
}

// Include the Calendar class
require_once 'calendar.php';

// Create a new instance of the Calendar class
$calendar = new Calendar(date("Y-m-d"));

// Validate and sanitize input
$event_title = trim($_POST['reason']);
$event_date = trim($_POST['date']);
$doctor_id = trim($_POST['patient']);
$hospital = trim($_POST['hospital']);
$time = trim($_POST['time']);
$color = "1"; 

// Start a transaction to ensure data consistency
$mysqli->begin_transaction();

try {
    // Fetch nurse ID associated with the selected doctor
    $sqlNurse = "SELECT nurse_id FROM nurse WHERE assigned_dr = ?";
    $stmtNurse = $mysqli->prepare($sqlNurse);
    if (!$stmtNurse) {
        throw new Exception("Failed to prepare nurse fetch statement: " . $mysqli->error);
    }

    $stmtNurse->bind_param("i", $doctor_id);
    $stmtNurse->execute();
    $resultNurse = $stmtNurse->get_result();

    if ($resultNurse->num_rows === 0) {
        throw new Exception("No nurse found for the selected doctor.");
    }

    $nurse = $resultNurse->fetch_assoc();
    $nurse_id = $nurse['nurse_id'];
    $patient_id = $_SESSION['patient_id'];

    // Add the appointment to the calendar
    $calendar->request_event($doctor_id, $nurse_id, $patient_id, $event_date, $time, $event_title, $hospital);
    $sqlAppt = "SELECT * FROM appointmentrequest WHERE patient_id = ?";
    $stmtAppt = $mysqli->prepare($sqlAppt);
    if (!$stmtAppt) {
        throw new Exception("Failed to prepare appointment request statement: " . $mysqli->error);
    }
    $stmtAppt->bind_param("i", $patient_id);
    $stmtAppt->execute();
    $resultAppt = $stmtAppt->get_result();
    $appointmentRes = $resultAppt->fetch_assoc();
    $appointment_id = $appointmentRes['req_appointment_id'];
   

    // Insert a notification for the doctor and nurse
    $notificationMessage = "You have a new appointment request.";
    $sqlNotify = "INSERT INTO notification (appt_id, dr_id, nurse_id, patient_id, reason) VALUES (?, ?, ?, ?, ?)";
    $stmtNotify = $mysqli->prepare($sqlNotify);

    if (!$stmtNotify) {
        throw new Exception("Failed to prepare notification statement: " . $mysqli->error);
    }

    $stmtNotify->bind_param("iiiis", $appointment_id, $doctor_id, $nurse_id, $patient_id, $notificationMessage);
    if (!$stmtNotify->execute()) {
        throw new Exception("Failed to send notification.");
    }

    // Commit the transaction
    $mysqli->commit();

    // Redirect back to the nurse page
    header("Location: patient_home_paid.php?success=Appointment requested successfully.");
    exit();

} catch (Exception $e) {
    // Rollback on error
    $mysqli->rollback();
    echo "Error: " . $e->getMessage();
} finally {
    // Close prepared statements
    if (isset($stmtNurse)) $stmtNurse->close();
    if (isset($stmtNotify)) $stmtNotify->close();
    $mysqli->close();
}
?>
