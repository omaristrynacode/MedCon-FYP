<?php
// Check if the required data is received via POST
if (isset($_POST['reason']) && isset($_POST['appt_id'])) {
    // Sanitize inputs to avoid injection attacks or other issues
    $reason = htmlspecialchars($_POST['reason']);
    $appt_id = intval($_POST['appt_id']); // Convert appt_id to integer
    $nurse_id = $_POST["nurse_id"];
    if(!isset($nurse_id)){
        echo "DIE";
    }
    include "../admin/conn.php";
    session_start();

    // Check if the appointment exists
    $sqlappt = "SELECT * FROM appointment WHERE appointment_id = ?";
    $stmt = $mysqli->prepare($sqlappt);
    $stmt->bind_param("i", $appt_id);
    $stmt->execute();
    $resultappt = $stmt->get_result();

    if ($resultappt->num_rows > 0) {
        // The appointment exists, fetch appointment details
        $appt = $resultappt->fetch_assoc();
       
        $patient_id = $appt["patient_id"];
        $dr_id = $appt["dr_id"];

        // Check if a notification for this appointment already exists
        $sqlCheckNoti = "SELECT * FROM notification WHERE appt_id = ?";
        $stmtCheck = $mysqli->prepare($sqlCheckNoti);
        $stmtCheck->bind_param("i", $appt_id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            // Notification for this appointment already exists
            http_response_code(409); // 409 Conflict
            echo json_encode(['status' => 'error', 'message' => 'Notification for this appointment already exists.']);
        } else {
            // Insert data into notification table
            $sqlInsert = "INSERT INTO notification (appt_id, nurse_id, patient_id, dr_id, reason) VALUES (?, ?, ?, ?, ?)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $stmtInsert->bind_param("iiiss", $appt_id, $nurse_id, $patient_id, $dr_id, $reason);

            if ($stmtInsert->execute()) {
                http_response_code(201); // 201 Created
                echo json_encode(['status' => 'success', 'message' => 'Notification created successfully.']);
            } else {
                http_response_code(500); // 500 Internal Server Error
                echo json_encode(['status' => 'error', 'message' => 'Failed to create notification.']);
            }

            $stmtInsert->close();
        }
        $stmtCheck->close();
    } else {
        // Appointment not found
        http_response_code(404); // 404 Not Found
        echo json_encode(['status' => 'error', 'message' => 'Appointment not found.']);
    }

    // Close database connection
    $stmt->close();
    $mysqli->close();
} else {
    // Handle missing required fields
    http_response_code(400); // 400 Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request. Missing reason or appt_id.']);
}
