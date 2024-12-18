<?php
session_start();
require_once 'Dr_calendar.php';

// Check if user is logged in
if (!isset($_SESSION['sess_user'])) {
    header("Location: login.php");
    exit();
}

// Validate and sanitize input
$event_title = trim($_POST['reason']);
$event_date = trim($_POST['date']);
$patient = trim($_POST['patient']);
$hospital = trim($_POST['hospital']);
$time = trim($_POST['time']);
$color = "1";

// Debugging: Check received values
error_log("Received hospital: " . $hospital);
error_log("Received patient: " . $patient);
error_log("Received date: " . $event_date);
error_log("Received time: " . $time);
error_log("Received reason: " . $event_title);

// Check if all fields are populated
if (empty($patient) || empty($event_date) || empty($time) || empty($event_title) || empty($hospital)) {
    error_log("Missing required fields");
    exit("Error: Missing required fields");
}

// Include Calendar class and add event
$calendar = new Calendar(date("Y-m-d"));
$calendar->add_event($_SESSION["assigned_dr"], $_SESSION['nurse_id'], $patient, $event_date, $time, $event_title, $hospital);

// Redirect back
header("Location: nursepage.php");
exit();
?>
