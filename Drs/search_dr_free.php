<?php
// Connect to your database
include('../admin/conn.php'); // Replace with your actual database connection file

// Check if a search term was provided
$search_term = isset($_POST['search']) ? $_POST['search'] : '';

// Base query to fetch all doctors
$query = "SELECT fname, lname, specialty, DoS, rating, dr_id, hosp_id FROM doctor";

// Modify the query if a search term is provided
if (!empty($search_term)) {
    $query .= " WHERE (fname LIKE ? OR lname LIKE ? OR specialty LIKE ?)";
}

// Prepare the SQL statement
if ($stmt = $mysqli->prepare($query)) {
    
    // If there is a search term, bind the parameters
    if (!empty($search_term)) {
        $search_term = '%' . $search_term . '%';
        $stmt->bind_param('sss', $search_term, $search_term, $search_term);
    }

    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Output the result in table rows
    while ($row = $result->fetch_assoc()) {
        $hosp_id = $row["hosp_id"];
        if($hosp_id == 1){
            $hospname = "American University of Beirut Medical Center";
        }elseif($hosp_id == 2){
            $hospname = "Rafik Hariri University Hospital";
        }elseif($hosp_id == 3){
            $hospname = "Hammoud Hospital University Medical Center";
        }
        else{
            echo "No Hospital ID given";
        }
        echo "<tr>
                <td>{$row['fname']} {$row['lname']}</td>
                <td>{$row['specialty']}</td>
                <td>{$hospname}</td>
                <td>{$row['DoS']}</td>
                <td>{$row['rating']}</td>
              </tr>";
    }

    // Close the statement and the connection
    $stmt->close();
} else {
    // Output error if query preparation fails
    echo "Error preparing query: " . $mysqli->error;
}

$mysqli->close();
?>
