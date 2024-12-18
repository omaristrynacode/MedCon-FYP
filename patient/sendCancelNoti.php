<?php
include "../admin/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'] ?? null; // Get the appointment ID from the request

    if ($appointment_id) {
        // Start a transaction to ensure consistency
        $mysqli->begin_transaction();

        try {
            // Fetch doctor and nurse IDs associated with the appointment
            $sqlFetch = "SELECT dr_id, nurse_id, patient_id FROM appointment WHERE appointment_id = ?";
            $stmtFetch = $mysqli->prepare($sqlFetch);

            if (!$stmtFetch) {
                throw new Exception("Failed to prepare fetch statement: " . $mysqli->error);
            }

            $stmtFetch->bind_param("i", $appointment_id);
            $stmtFetch->execute();
            $result = $stmtFetch->get_result();

            if ($result->num_rows === 0) {
                throw new Exception("Appointment not found.");
            }

            $appointment = $result->fetch_assoc();
            $doctor_id = $appointment['dr_id'];
            $nurse_id = $appointment['nurse_id'];
            $patient_id = $appointment['patient_id'];

            // Delete the appointment
            $sqlDelete = "DELETE FROM appointment WHERE appointment_id = ?";
            $stmtDelete = $mysqli->prepare($sqlDelete);

            if (!$stmtDelete) {
                throw new Exception("Failed to prepare delete statement: " . $mysqli->error);
            }

            $stmtDelete->bind_param("i", $appointment_id);
            if (!$stmtDelete->execute()) {
                throw new Exception("Failed to delete appointment.");
            }

            // Insert cancellation notification for the doctor
            $notificationMessage = "This appointment has been cancelled.";
            $sqlNotify = "INSERT INTO notification (appt_id, dr_id, nurse_id,patient_id, reason) VALUES (?, ?, ?, ?, ?)";
            $stmtNotify = $mysqli->prepare($sqlNotify);

            if (!$stmtNotify) {
                throw new Exception("Failed to prepare notification statement: " . $mysqli->error);
            }

            $stmtNotify->bind_param("iiiis", $appointment_id, $doctor_id, $nurse_id,$patient_id, $notificationMessage);
            if (!$stmtNotify->execute()) {
                throw new Exception("Failed to insert notification.");
            }

            // Commit the transaction
            $mysqli->commit();

            echo json_encode(['success' => true, 'message' => 'Appointment cancelled and notifications sent.']);

        } catch (Exception $e) {
            // Rollback the transaction on error
            $mysqli->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        } finally {
            // Close the statements
            if (isset($stmtFetch)) $stmtFetch->close();
            if (isset($stmtDelete)) $stmtDelete->close();
            if (isset($stmtNotify)) $stmtNotify->close();
            $mysqli->close();
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid appointment ID.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
