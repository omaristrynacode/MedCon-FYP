<?php 
include "../admin/conn.php";
session_start();
$nurse_id = $_SESSION["patient_id"];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['inputEmail4Modal'];
    $phone = $_POST['inputPhone4Modal'];    
    $address = $_POST['inputAddress'];    

    $sql = $mysqli->prepare("UPDATE patient SET email=?, phone=?, address=? WHERE patient_id=?");
    $sql->bind_param("sssi",$email,$phone,$address, $nurse_id);
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