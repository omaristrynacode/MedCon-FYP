<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])&& isset( $_POST['appt_id'])&& isset($_POST['test_type'])&& isset($_POST['test_type'])) {
    include '../admin/conn.php'; // Database connection

    // Retrieve form inputs
    $appt_id = $_POST['appt_id']; // Assume patient ID is sent from the frontend
    $test_type = $_POST['test_type'];
    $test_date = $_POST['test_date'];
    $apptsql = sprintf("SELECT * FROM appointment WHERE appointment_id='%s'",$mysqli->real_escape_string($appt_id));
    $stmt1 = $mysqli->prepare($apptsql);
    $stmt1->execute();
    $ress = $stmt1->get_result();
    $result1 = $ress->fetch_assoc();
    $dr_id = $result1["dr_id"];
    $nurse_id = $result1["nurse_id"];
    $patient_id = $result1["patient_id"];
    $apttttID = $result1["appointment_id"];

    // Check if a file is uploaded
    if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['test_file']['tmp_name'];
        $file_name = $_FILES['test_file']['name'];
        $file_size = $_FILES['test_file']['size'];
        $file_type = $_FILES['test_file']['type'];

        // Validate file type (only PDF)
        if ($file_type !== 'application/pdf') {
            die("Only PDF files are allowed.");
        }

        // Read the file content
        $file_content = file_get_contents($file_tmp);



        // Insert into the database
        $sql = "INSERT INTO tests (dr_id, nurse_id, patient_id, test, date, test_type, push, appointment_id) 
                VALUES (?, ?, ?, ?, ?, ?, 0, ?)"; // Default 'push' value to 0
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $stmt->bind_param("iiisssi", $dr_id, $nurse_id, $patient_id, $file_content, $test_date, $test_type, $appt_id);
        if ($stmt->execute()) {
            echo "Test uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "File upload error!";
    }
} else {
    echo "Invalid request!";
}
?>
