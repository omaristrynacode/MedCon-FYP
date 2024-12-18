<?php
if (isset($_POST['pdf_data'])) { // Check POST instead of GET
    include '../admin/conn.php';

    $pdf_id = $_POST['pdf_data']; // Fetch from POST
    $query = "SELECT test FROM tests WHERE test_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $pdf_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $pdf_content = $row['test']; // Decompress if compressed
        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=\"test_result.pdf\"");
echo `<link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">`;
        
        echo $pdf_content;
    } else {
        echo "File not found.";
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "No file specified.";
}

?>
