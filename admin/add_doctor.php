<?php
session_start();
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorFirstName = $_POST["firstName"];
    $doctorLastName = $_POST["lastName"];
    $doctorPhone = $_POST["phone"];
    $doctorEmail = $_POST["email"];
    $doctorSpecialty = $_POST["specialty"];
    $doctorStartDate = $_POST["dos"];
    $doctorPassword = $_POST["pass"];

    $sql = "INSERT INTO doctor (fname, lname, phone, email, specialty, DoS, password, status)
            VALUES ('$doctorFirstName', '$doctorLastName', '$doctorPhone', '$doctorEmail', '$doctorSpecialty', '$doctorStartDate', '$doctorPassword', 'active')";
$result = $mysqli->query($sql);
    if ($result) {
        header("Location: ./superadmin.php");
        echo "Doctor added successfully!";
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }
}


?>