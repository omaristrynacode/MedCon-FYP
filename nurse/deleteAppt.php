<?php
// Check if the required data is received via POST
if (isset($_POST['appt_id'])) {
    // Sanitize the input to prevent SQL injection or other attacks
    $appt_id = intval($_POST['appt_id']); // Convert appt_id to an integer

    include "../admin/conn.php"; // Include your database connection

    // Check if the appointment exists
    $sqlCheck = "SELECT * FROM appointment WHERE appointment_id = ?";
    $stmt = $mysqli->prepare($sqlCheck);
    $stmt->bind_param("i", $appt_id);
    $stmt->execute();
    $resultCheck = $stmt->get_result();

    if ($resultCheck->num_rows > 0) {
        // Appointment exists, proceed to delete it
        $sqlDelete = "DELETE FROM appointment WHERE appointment_id = ?";
        $stmtDelete = $mysqli->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $appt_id);

        if ($stmtDelete->execute()) {
            http_response_code(200); // 200 OK
            echo json_encode(['status' => 'success', 'message' => 'Appointment deleted successfully.']);
        } else {
            http_response_code(500); // 500 Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete the appointment.']);
        }

        $stmtDelete->close();
    } else {
        // Appointment not found
        http_response_code(404); // 404 Not Found
        echo json_encode(['status' => 'error', 'message' => 'Appointment not found.']);
    }

    // Close the database connection
    $stmt->close();
    $mysqli->close();
} else {
    // Missing required fields
    http_response_code(400); // 400 Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request. Missing appt_id.']);
}
?>
