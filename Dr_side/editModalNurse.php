<?php 
include "../admin/conn.php";
session_start();
$nurse_id = $_SESSION["dr_id"];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['inputEmail4Modal'];
    $phone = $_POST['inputPhone4Modal'];    

    $sql = $mysqli->prepare("UPDATE doctor SET email=?, phone=? WHERE dr_id=?");
    $sql->bind_param("ssi",$email,$phone,$nurse_id);
    if($sql->execute()){
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $sql->error]);
    }

    $sql->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Nurse ID not provided.']);
}

$mysqli->close();
?>