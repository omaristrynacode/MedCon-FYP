<?php
session_start();
$username = isset($_SESSION['sess_user']) ? $_SESSION['sess_user'] : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCon - Admin Dashboard</title>
    <link rel="stylesheet" href="super.css?v=<?= rand(1, 1000) ?>">
    <script src="superpatient.js" defer></script>
    <script src="https://kit.fontawesome.com/34652f094f.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/34652f094f.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js" integrity="sha512-2rNj2KJ+D8s1ceNasTIex6z4HWyOnEYLVC3FigGOmyQCZc2eBXKgOxQmo3oKLHyfcj53uz4QMsRCWNbLd32Q1g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    


</head>
<body>
<header>
    <nav class="navbar navbar-dark bg-dark sticky-navbar">
    <div class="logo">
            <h1>MedCon</h1>
        </div>
      
        <div class="toggle-switch" id="darkModeSwitch">
        <a href="/MedCon/index/index.php"><i class='bx bx-log-out'></i></a>
        <span>  <i class='bx bx-user-plus' onclick="openaddmodal()" ></i></span>

        <i class='bx bx-moon' id="moon"></i>
        </div>
    </nav>
</header>

<main class="container">
    <div class="drtable">
    <h1 class="title">Doctor's List</h1>
    <div class="search-bar">
    <input type="text" id="searchBar" class="search-bar" placeholder="Search by name, phone, email, or specialty..." />
    <i class='bx bx-search-alt-2'id="magni" ></i>
    </div>
    <div id="specialtyBubbles" class="buttons bubbles-container"></div>
    <div class="form-check form-switch d-flex justify-content-between align-items-center">
    <h4 id="titledrs">Active Doctors:</h4>
 

    <div>
        <label class="form-check-label" for="flexSwitchCheckDefault">Toggle Archive </label>
        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
    </div>
</div>
<div id="AddModalCenter" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <div class="btn-group">
    <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" 
            data-bs-toggle="dropdown" id="dropdowntbtn"aria-expanded="false">
        Add Doctor
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="#" onclick="adddoctor()" data-action="add_doctor.php" data-entity="doctor">
                Add Doctor
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" onclick="addnurse()" data-action="add_nurse.php" data-entity="nurse">
                Add Nurse
            </a>
        </li>
    </ul>
</div>

                <button type="button" class="btn-close close-button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="AddDoctorForm" class="form-group" action="add_doctor.php" method="POST">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name:</label>
                        <input type="text" id="newfirstName" name="firstName" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name:</label>
                        <input type="text" id="newlastName" name="lastName" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="text" id="newphone" name="phone" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="newemail" name="email" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="newpass" class="form-label">Password:</label>
                        <input type="password" id="newpass" name="pass" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3" id="dos">
                        <label for="newdos" class="form-label">Date of Start:</label>
                        <input type="date" id="newdos" name="dos" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3" id="hospitalField" style="display: none;">
                        <label for="hosp" class="form-label">Hospital:</label>
                        <select name="hosp" id="hosp" class="form-control search-bar" >
                        <?php
                        include "./conn.php";
                              $sql = "SELECT * FROM hospital";
                              $result = $mysqli->query($sql);
                              while ($row = $result->fetch_assoc()) {
                                  echo "<option value='{$row['Hospital_ID']}'>{$row['hosp_name']}</option>";
                              }
                              ?>
                        </select>
                    </div>

                    <div class="mb-3" id="assignedToField" style="display: none;">
                        <label for="assto" class="form-label">Assigned To:</label>
                        <select name="assto" id="assto" class="form-control search-bar" >
                        <?php
                              $sql = "SELECT * FROM doctor";
                              $result = $mysqli->query($sql);
                              while ($row = $result->fetch_assoc()) {
                                  echo "<option value='{$row['dr_id']}'>{$row['fname']} {$row['lname']}</option>";
                              }
                              ?>
                        </select>
                    </div>

                    <div class="mb-3" id="specialty">
                        <label for="newspecialty" class="form-label">Specialty:</label>
                        <input type="text" id="newspecialty" name="specialty" class="form-control search-bar" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <table class="table">
        <thead style="background-color: blue;">
    
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
       

        </tbody>
        <tfoot  style="background-color: blue;">
        </tfoot>
    </table>
    <!-- Modal Structure -->
    <div id="exampleModalCenter" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Doctor</h2>
                <button type="button" class="btn-close close-button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDoctorForm" class="form-group">
                    <input type="hidden" id="doctorId" name="doctorId">
                    
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name:</label>
                        <input type="text" id="firstName" name="firstName" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="specialtyint" class="form-label">Specialty:</label>
                        <input type="text" id="specialtyint" name="specialty" class="form-control search-bar" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <div id="loadingSpinner" class="spinner" style="display: none;"></div>
    <div class="pagination" id="pagination">
        <div class="pagination-cover">
        <a id="prevPage" ><<</a>
        <span id="pageNumbers"></span>
        <a id="nextPage" >>></a>
        </div>
    </div>

<hr>
     <!-- NURSE TABLE//////////////////////////////////////////////////////////////// -->
    <div class="nursestable">
    <h1 class="title">Nurse's List</h1>
    <div class="search-bar">
        <input type="text" id="nurseSearchBar" class="search-bar" 
               placeholder="Search by name, phone, email, or hospital..." oninput="filterNurses()"/>
        <i class='bx bx-search-alt-2' id="magni"></i>
    </div>
    <div  class="buttons bubbles-container" id="hospbubbles">
        
    </div>

    <div class="form-check form-switch d-flex justify-content-between align-items-center">
        <h4 id="toggletitle">Active Nurses:</h4>
        <div>
            <label class="form-check-label" for="flexSwitchCheckNurse">Toggle Archive</label>
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckNurse">
        </div>
    </div>

    <table class="table">
        <thead style="background-color: green;">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Password</th>
                <th id="hospth">Hospital</th>
                <th>Assigned Doctor</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="nurses-list">
        </tbody>
    </table>

    <!-- Modal Structure -->
    <div id="nurseModalCenter" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Nurse</h2>
                <button type="button" class="btn-close close-button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editNurseForm" class="form-group">
                    <input type="hidden" id="nurseId" name="nurseId">

                    <div class="mb-3">
                        <label for="nurseFirstName" class="form-label">First Name:</label>
                        <input type="text" id="nurseFirstName" name="firstName" 
                               class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="nurseLastName" class="form-label">Last Name:</label>
                        <input type="text" id="nurseLastName" name="lastName" 
                               class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="nursePhone" class="form-label">Phone:</label>
                        <input type="text" id="nursePhone" name="phone" 
                               class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="nurseEmail" class="form-label">Email:</label>
                        <input type="email" id="nurseEmail" name="email" 
                               class="form-control search-bar" required>
                    </div>

                    <div class="mb-3">
                        <label for="nurseHospital" class="form-label">Hospital:</label>
                        <select id="nurseHospital" name="hospital" class="form-control search-bar" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="assignedDoctor" class="form-label">Assigned Doctor:</label>
                        <select id="assignedDoctor" name="assignedDoctor" class="form-control search-bar" required></select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



    <div id="loadingSpinner" class="spinner" style="display: none;"></div>
    <div class="pagination" id="pagination">
        <div class="pagination-cover">
            <a id="prevNursePage" ><<</a>
            <span id="nursePageNumbers"></span>
            <a id="nextNursePage" >>></a>
        </div>
    </div>
</div>
<hr>
     <!-- PATIENT TABLE//////////////////////////////////////////////////////////////// -->
<div class="patientstable">
    <h1 class="title">Patient's List</h1>
    <div class="search-bar">
        <input type="text" id="patientSearchBar" class="search-bar" 
               placeholder="Search by name, phone, email, or doctor..." oninput="filterPatients()"/>
        <i class='bx bx-search-alt-2' id="magni"></i>
    </div>
    <div class="buttons bubbles-container" id="doctorbubbles"></div>

    <div class="form-check form-switch d-flex justify-content-between align-items-center">
        <h4 id="toggletitle">Active Patients:</h4>
        <div class="form-group">
        
            <select class="form-select" id="patientStatusSelect">
                <option value="all">All Patients</option>
                <option value="free">Free</option>
                <option value="paid">Paid</option>
            </select>
        </div>

    </div>

    <table class="table">
        <thead style="background-color: blue;">
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Date of Birth</th>
                <th>Subscription</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="patients-list"></tbody>
    </table>

    <!-- Modal Structure -->
    <div id="patientModalCenter" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Edit Patient</h2>
                    <button type="button" class="btn-close close-button" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPatientForm" class="form-group">
                        <input type="hidden" id="patientId" name="patientId">

                        <div class="mb-3">
                            <label for="patientFirstName" class="form-label">First Name:</label>
                            <input type="text" id="patientFirstName" name="firstName" 
                                   class="form-control search-bar" required>
                        </div>

                        <div class="mb-3">
                            <label for="patientLastName" class="form-label">Last Name:</label>
                            <input type="text" id="patientLastName" name="lastName" 
                                   class="form-control search-bar" required>
                        </div>

                        <div class="mb-3">
                            <label for="patientPhone" class="form-label">Phone:</label>
                            <input type="text" id="patientPhone" name="phone" 
                                   class="form-control search-bar" required>
                        </div>

                        <div class="mb-3">
                            <label for="patientEmail" class="form-label">Email:</label>
                            <input type="email" id="patientEmail" name="email" 
                                   class="form-control search-bar" required>
                        </div>
                        <div class="mb-3">
                            <label for="patientAddress" class="form-label">Address:</label>
                            <input type="text" id="patientAddress" name="address" 
                                   class="form-control search-bar" required>
                        </div>

                        <div class="mb-3">
                            <label for="patientDOB" class="form-label">Date of Birth:</label>
                            <input type="date" id="patientDOB" name="dob" 
                                   class="form-control search-bar" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingSpinner" class="spinner" style="display: none;"></div>
    <div class="pagination" id="pagination">
        <div class="pagination-cover">
            <a id="prevPatientPage"><<</a>
            <span id="patientPageNumbers"></span>
            <a id="nextPatientPage" >>></a>
        </div>
    </div>
</div>

    </div>


</main>

<footer class="footer navbar-dark bg-dark">
    <div class="footer-bottom">
        &copy; MedCon 2024. All rights reserved.
    </div>
</footer>


</body>
</html>
