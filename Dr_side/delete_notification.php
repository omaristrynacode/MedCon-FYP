<?php
include "../admin/conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noti_id = $_POST['noti_id'] ?? null;

    if ($noti_id) {
        $sql = "DELETE FROM notification WHERE noti_id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $noti_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete notification.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid notification ID.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
