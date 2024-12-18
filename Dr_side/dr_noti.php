<?php
include "../admin/conn.php";

session_start();
$dr_id = $_SESSION['dr_id']; // Doctor's ID

if (isset($dr_id)) {
    // Fetch all notifications for this doctor
    $sql = "
        SELECT 
            n.noti_id, 
            p.fname AS patient_fname, 
            p.lname AS patient_lname, 
            a.reason, 
            a.date 
        FROM 
            notification n 
        JOIN 
            patient p ON n.patient_id = p.patient_id 
        JOIN 
            appointment a ON n.appt_id = a.appointment_id 
        WHERE 
            n.dr_id = ?
        ORDER BY 
            a.date ASC"; // Ensure we're filtering notifications by dr_id

    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'error' => 'SQL prepare failed: ' . $mysqli->error]);
        exit;
    }

    $stmt->bind_param("i", $dr_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        // Simplify the notification details
        $appointment_date = new DateTime($row['date']);
        $current_date = new DateTime();
        $interval = $current_date->diff($appointment_date);
        $days_left = $interval->format('%r%a'); // Adds minus sign for past dates

        // Add notification data to the list
        $notifications[] = [
            'noti_id' => $row['noti_id'],
            'patient_name' => $row['patient_fname'] . ' ' . $row['patient_lname'],
            'reason' => $row['reason'],
            'date' => $row['date'],
            'days_left' => $days_left
        ];
    }

    $stmt->close();
    $mysqli->close();

    // Return all notifications as JSON
    header('Content-Type: application/json');
    echo json_encode($notifications);
} else {
    echo json_encode(['success' => false, 'error' => 'Doctor ID not provided.']);
}
?>
