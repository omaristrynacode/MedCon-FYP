<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if(isset($_POST["first_name_reg"], $_POST["last_name_reg"], $_POST["phone_reg"], $_POST["email_reg"], $_POST["pass_reg"], $_POST["address_reg"], $_POST["dob_reg"], $_POST["gender_reg"])){
    $first_name = $_POST["first_name_reg"];
    $last_name = $_POST["last_name_reg"];
    $phone = $_POST["phone_reg"];
    $email = $_POST["email_reg"];
    $password = $_POST["pass_reg"];
    $address = $_POST["address_reg"];
    $dob = $_POST["dob_reg"];
    $gender = $_POST["gender_reg"];

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    include "../admin/conn.php";

    // Check if email already exists
    $sql_email = "SELECT * FROM patient WHERE email = ?";
    $stmt_email = $mysqli->prepare($sql_email);
    if (!$stmt_email) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $result_email = $stmt_email->get_result();
    if($result_email->num_rows > 0){
        $error_message = "This email is already in use, please try again.";
        header("Location: ./signin.php?error_message=" . urlencode($error_message));
      
    } else {
        // Insert into database
        $free = "free";
        $sql = "INSERT INTO patient (fname, lname, phone, email, password, address, dob, paid) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }
        $stmt->bind_param("ssisssss", $first_name, $last_name, $phone, $email, $password_hash, $address, $dob, $free);
        $stmt->execute();

        if($stmt->affected_rows > 0){
            $patient_id = $stmt->insert_id;
            session_start();
            $_SESSION['sess_user'] = $first_name; 
            $_SESSION['name'] = $first_name . " " . $last_name; 
            $_SESSION['level'] = "patient";
            $_SESSION["patient_id"] = $patient_id;
            // $_SESSION["EMAIL"] = $email
            var_dump($_SESSION); // Check the session variables

            header("Location: ../patient/patient_home_free.php?patient_id=$patient_id");
            exit(); // Add exit to prevent further execution
        } else {
            $error_message = "Registration failed!";
        }
    }
}else{
    $error_message = "Registration failed!";
}
?>