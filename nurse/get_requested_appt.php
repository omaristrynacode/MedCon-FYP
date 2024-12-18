<?php
include "../admin/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notification_id = $_POST['notification_id'] ?? null;

    if ($notification_id) {
        // Query to fetch appointment information using notification ID
        $sql = "
            SELECT a.date, a.time, a.reason, h.hosp_name, p.fname, p.lname, p.patient_id, h.Hospital_ID
            FROM appointmentrequest AS a
            JOIN notification AS n ON n.appt_id = a.req_appointment_id
            JOIN hospital AS h ON a.hosp_id = h.Hospital_ID
            JOIN patient AS p ON a.patient_id = p.patient_id
            WHERE n.noti_id = ?
        ";

        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $notification_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $appointment = $result->fetch_assoc();
                echo json_encode([
                    'success' => true,
                    'date' => $appointment['date'],
                    'patient_id'=> $appointment['patient_id'],
                    'time' => $appointment['time'],
                    'reason' => $appointment['reason'],
                    'hosp_id' =>$appointment['Hospital_ID'],
                    'hosp_name' => $appointment['hosp_name'],
                    'fname' => $appointment['fname'],
                    'lname' => $appointment['lname'],
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Appointment not found.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to prepare query.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid notification ID.']);
    }
    $mysqli->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
