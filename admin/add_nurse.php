<?php
session_start();
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorFirstName = $_POST["firstName"];
    $doctorLastName = $_POST["lastName"];
    $doctorPhone = $_POST["phone"];
    $doctorEmail = $_POST["email"];
    $doctorSpecialty = $_POST["hosp"];
    $doctorStartDate = $_POST["assto"];
    $doctorPassword = $_POST["pass"];

    $sql = "INSERT INTO nurse (fname, lname, phone, email, hosp_id, assigned_dr, password, status)
            VALUES ('$doctorFirstName', '$doctorLastName', '$doctorPhone', '$doctorEmail', '$doctorSpecialty', '$doctorStartDate', '$doctorPassword', 'active')";
$result = $mysqli->query($sql);
    if ($result) {
        header("Location: ./superadmin.php");
        echo "Nurse added successfully!";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}


?>