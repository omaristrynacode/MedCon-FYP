<?php
session_start(); // Start the session to access session variables

// Check if the session variable containing the username is set
if (isset($_SESSION['sess_user'])) {
    $username = $_SESSION['sess_user']; // Retrieve the username
    $hosp_id = $_SESSION['hosp_id'];
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
} else {
    $username = ""; // Set a default value if the username is not set
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MedCon - Turning the Medical World Digitally">
    <meta name="keywords" content="medical, healthcare, digital, doctors, appointments">
    <title>MedCon - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css?v=<?= rand(1, 1000) ?>">
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">
    <!-- Scripts and Stylesheets -->
    <script src="https://kit.fontawesome.com/34652f094f.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@100..700" rel="stylesheet">
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">

</head>
<body>
<header>
    <div class="banner">
    <nav class="nav">
    <div class="userinfo"><?php echo "<span style='color: blue;'>". $username . "</span>, " . $hospname?></div>
    <div class="logo">
        <h1>MedCon</h1>
    </div>
    <ul class="nav-links">
        <li><a href="/MedCon/index/index.php">Sign Out</a></li>
    </ul>
</nav>
    </div>
</header>

<main class="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Doctors List</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Specialty</th>
                            <th>Date of Start</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody id="doctors-list">
                        <?php 
                        $_SESSION['hosp_id'] = $hosp_id;

                        include "fetch_drs.php";?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Add Doctor</h3>
                <form action="add_doctor.php" method="POST">
                    <div class="mb-3">
                        <label for="doctorFirstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" name="doctorFirstName" id="doctorFirstName" placeholder="Enter first name">
                    </div>
                    <div class="mb-3">
                        <label for="doctorLastName" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" name="doctorLastName" id="doctorLastName" placeholder="Enter last name">
                    </div>
                    <div class="mb-3">
                        <label for="doctorPhone" class="form-label">Phone:</label>
                        <input type="tel" class="form-control" name="doctorPhone" id="doctorPhone" placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label for="doctorEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="doctorEmail" id="doctorEmail" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label for="doctorSpecialty" class="form-label">Specialty:</label>
                        <input type="text" class="form-control"name="doctorSpecialty" id="doctorSpecialty" placeholder="Enter specialty">
                    </div>
                    <div class="mb-3">
                        <label for="doctorStartDate" class="form-label">Date of Start:</label>
                        <input type="date" class="form-control" name="doctorStartDate" id="doctorStartDate">
                    </div>

                    <div class="mb-3">
                        <label for="doctorPassword" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="doctorPassword" id="doctorPassword" placeholder="Enter password">
                    </div>
                    <input type="hidden" name="hosp_id" value="<?php echo $hosp_id; ?>">
                    <button type="submit" class="btn btn-primary">Add Doctor</button>
                </form>
            </div>
        </div>
        <?php
include "conn.php";

// Get the doctor ID from the URL parameter
$dr_id = $_GET['dr_id'];

// Check if the ID is valid
if (isset($dr_id) && is_numeric($dr_id)) {
    // Retrieve the doctor's information from the database
    $query = "SELECT * FROM doctor WHERE dr_id = '$dr_id'";
    $result = $mysqli->query($query);

    if ($result !== false && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Redirect to the previous page with an error message
        header("Location: doctors_list.php?msg=Doctor not found!");
        exit;
    }
} else {
    // Redirect to the previous page with an error message
    header("Location: doctors_list.php?msg=Invalid doctor ID!");
    exit;
}

// Create a modal to edit the doctor's information
?>
<!-- Modal -->



        <div class="row">
            <div class="col-md-12">
                <h2>Nurses List</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody id="nurses-list">
                        <!-- Nurses list will be displayed here -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Add Nurse</h3>
                <form>
                    <div class="mb-3">
                        <label for="nurseFirstName" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="nurseFirstName" placeholder="Enter first name">
                    </div>
                    <div class="mb-3">
                        <label for="nurseLastName" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="nurseLastName" placeholder="Enter last name">
                    </div>
                    <div class="mb-3">
                        <label for="nursePhone" class="form-label">Phone:</label>
                        <input type="tel" class="form-control" id="nursePhone" placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label for="nurseEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="nurseEmail" placeholder="Enter email">
                    </div>
        
                    <div class="mb-3">
                        <label for="nursePassword" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="nursePassword" placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Nurse</button>
                </form>
            </div>
        </div>
    </div>
</main>
<footer class="footer">
        <div class="inner-footer">
            <div class="footer-items">
                <h2>MedCon</h2>
                <div class="border"></div>
                
        </div>
        <div class="footer-bottom">
            &copy; MedCon 2024. All rights reserved.
        </div>
    </footer>
</body>
</html>
