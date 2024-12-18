<?php
session_start();
$assigneddr = $_SESSION["assigned_dr"];
$_SESSION["assigned_dr"] = $assigneddr;
require_once 'calendar.php'; 

$year = $_POST['year'];
$month = $_POST['month'];

$calendar = new Calendar(date($year. "/" . $month . "/d"));
echo $calendar->__toString();
?>