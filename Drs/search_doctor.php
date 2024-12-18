<?php
// Connect to your database
include('../admin/conn.php'); // Replace with your actual database connection file

if (isset($_POST['hosp_id'])) {
    $hosp_id = $_POST['hosp_id'];
    $search_term = isset($_POST['search']) ? $_POST['search'] : '';

    // Base query to fetch doctors based on hosp_id
    $query = "SELECT fname, lname, specialty, DoS, rating, dr_id FROM doctor WHERE hosp_id = ?";

    // If a search term is provided, update the query to include it
    if (!empty($search_term)) {
        $query .= " AND (fname LIKE ? OR lname LIKE ? OR specialty LIKE ?)";
    }

    // Prepare the SQL statement
    if ($stmt = $mysqli->prepare($query)) {
        
        // If there is a search term, bind additional parameters
        if (!empty($search_term)) {
            $search_term = '%' . $search_term . '%';
            $stmt->bind_param('isss', $hosp_id, $search_term, $search_term, $search_term);
        } else {
            $stmt->bind_param('i', $hosp_id);
        }

        // Execute the prepared statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Output the result in table rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['fname']} {$row['lname']}</td>
                    <td>{$row['specialty']}</td>
                    <td>{$row['DoS']}</td>
                    <td>{$row['rating']}</td>
                    <td><a href='book_appointment.php?doctor_id={$row['dr_id']}' class='btn btn-primary'>Book</a></td>
                  </tr>";
        }

        // Close the statement and the connection
        $stmt->close();
    } else {
        // Output error if query preparation fails
        echo "Error preparing query: " . $mysqli->error;
    }

    $mysqli->close();
}
?>
