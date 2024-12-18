<?php
include '../admin/conn.php';

$nurse_id = $_POST['dr_id'];
$search_term = isset($_POST['search']) ? $_POST['search'] : '';
$test_type = isset($_POST['test_type']) ? $_POST['test_type'] : 'all';
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Base query
$query = "SELECT t.*, p.fname AS patient_fname, p.lname AS patient_lname 
          FROM tests t
          JOIN patient p ON t.patient_id = p.patient_id
          WHERE t.dr_id = ?";

// Array to hold the types of parameters (e.g., 's' for string, 'i' for integer)
$types = 'i'; // Nurse ID is an integer, so start with 'i'
$params = [$nurse_id]; // Store parameters in an array

// Add search term filtering
if (!empty($search_term)) {
    $query .= " AND (p.fname LIKE ? OR p.lname LIKE ?)";
    $types .= 'ss'; // Both fname and lname are strings
    $search_term = '%' . $search_term . '%';
    array_push($params, $search_term, $search_term);
}

// Add test type filtering if it's not 'all'
if ($test_type != 'all') {
    $query .= " AND t.test_type = ?";
    $types .= 's'; // test_type is a string
    array_push($params, $test_type);
}

// Add date range filtering if both dates are provided
if (!empty($start_date) && !empty($end_date)) {
    $query .= " AND t.date BETWEEN ? AND ?";
    $types .= 'ss'; // Dates are strings
    array_push($params, $start_date, $end_date);
}

// Prepare the statement
if ($stmt = $mysqli->prepare($query)) {
    
    // Use dynamic call to bind_param with the parameter types and values
    if (count($params) > 1) {
        $stmt->bind_param($types, ...$params); // Spread the $params array
    } else {
        $stmt->bind_param('i', $nurse_id); // Only nurse_id is provided
    }

    // Execute the statement and fetch the results
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the results in table rows
    while ($row = $result->fetch_assoc()) {
        
        echo "
        <tr>
            <td>{$row['patient_fname']} {$row['patient_lname']}</td>
            <td>{$row['test_type']}</td>
            <td>{$row['date']}</td>
            <td>
                <form action='view_pdf_inline.php' method='POST' target='_blank'>
                    <input type='hidden' name='pdf_data' value='{$row['test_id']}'>
                    <button type='submit' class='btn btn-primary'>View PDF</button>
                </form>
            </td>
            <td><a class='btn btn-danger'>Archive</a></td>
        </tr>";
    }

    $stmt->close();
} else {
    echo "Error preparing query: " . $mysqli->error;
}

$mysqli->close();
?>
