<?php
include "../admin/conn.php";

session_start();
$nurse_id = $_SESSION['nurse_id']; // Nurse's ID

if (isset($nurse_id)) {
    // Fetch all notifications for this nurse
    $sql = "SELECT 
                n.noti_id, 
                p.fname AS patient_fname, 
                p.lname AS patient_lname, 
                n.reason, 
                COALESCE(a.date, NOW()) AS date 
            FROM 
                notification n 
            JOIN 
                patient p ON n.patient_id = p.patient_id 
            LEFT JOIN 
                appointment a ON n.appt_id = a.appointment_id 
            WHERE 
                n.nurse_id = ?
            ORDER BY 
                COALESCE(a.date, NOW()) ASC";

    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $mysqli->error]);
        exit;
    }

    $stmt->bind_param("i", $nurse_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'noti_id' => $row['noti_id'],
            'patient_name' => $row['patient_fname'] . ' ' . $row['patient_lname'],
            'reason' => $row['reason'],
            'date' => $row['date']
        ];
    }

    $stmt->close();
    $mysqli->close();

    // Return all notifications as JSON
    header('Content-Type: application/json');
    echo json_encode($notifications);

} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Nurse ID not provided.']);
}
