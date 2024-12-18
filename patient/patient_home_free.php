<?php session_start();

include 'calendar.php';
$calendar = new Calendar(date("Y/m/d"));


if (isset($_SESSION['sess_user'])) {
 
} else {
  $username = ""; // Set a default value if the username is not set
}
$username = $_SESSION['sess_user']; // Retrieve the username
$name_member = $_SESSION['name'];
  $patient_id = $_SESSION["patient_id"];
  $_SESSION["patient_id"] = $patient_id;
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MedCon - Turning the Medical World Digitally">
    <meta name="keywords" content="medical, healthcare, digital, doctors, appointments">

    <title>MedCon - Turning the Medical World Digitally</title>

    <link rel="stylesheet" href="patient_home.css?v=<?= rand(1, 1000) ?>">
    <link rel="stylesheet" href="patientcalender.css?v=<?= rand(1, 1000) ?>">
<link rel="icon" href="../img/logo3.png" size="32x32" type="image/x-icon">


    <!-- Scripts and Stylesheets -->
    <script src="https://kit.fontawesome.com/34652f094f.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@100..700" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<style>
   .modal-btn-notify {
    display: none !important;
}
</style>
</head>
<body>
 
   
   
    

    <main class="body">
       

    <div  id="profile-info">
        <div class="curtain">
            <header>
                <div class="banner">
                    <div class="navbar">
                        <div class="logo">
                            <h1>MedCon</h1>
                        </div>
                        <div class="logo" id="navlink">
                            <a href="#calfornav">View Appointments</a>
                            <a href="#testblockfornav">Tests</a>
                            <a href="/MedCon/Drs/dr_view_free.php">View Doctors</a>
                            <a href="#" id="profilemodal" data-bs-toggle="modal" data-bs-target="#editModal">Profile</a>
                            <a href="#" class="upgrade-link">Upgrade</a>
                        </div>
                    
                        <div class="logo datetime">
                            <div class="sameline">
                                <a href="../index/index.php" id="logout"><i class='bx bx-log-out'></i></a>
                                <a  id="logout" class="notif"><i class='bx bxs-bell-ring'></i></a>
                                <div class="dropdown" id="notificationDropdown"></div>
                            </div>
                            <div class="aboveeachother">
                                <h4 class="time"></h4>
                                <p class="date"></p>

                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="profile">
                <img src="../img/dr_face.jpg" alt="Image" class="pfp-pic">
                <p>Welcome back,<span><?php echo $name_member; ?></span></p>
                <span><p id="hosp"></p></span>
            </div>
        </div>
                    <?php 
                include '../admin/conn.php';
                $sql = sprintf("SELECT * FROM patient WHERE patient_id='%s'",
                $mysqli->real_escape_string($patient_id));
                $result =$mysqli->query($sql);
                $drres = $result->fetch_assoc();
                $dremail = $drres["email"];
                $drphone = $drres["phone"];
                $addy = $drres["address"];
                $subscription = $drres["paid"];
               
                $assigneddr = $drres["fname"] . " " . $drres["lname"];
                $drfname = $drres["fname"];
                $drlname = $drres["lname"];
         
                    ?>
        
    </div>        


<!-- Modal Edit profile -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #011a27;">
          <img src="/MedCon/img/dr_face.jpg" alt="Image" class="pfp-pic">

        <div class="userinfo">
            <h5 class="modal-title" id="editModalLabel"><?php echo $drfname . " " . $drlname;?></h5>
            <h6 style="font-weight: lighter;"><?php echo $dremail;?></h6>
        </div>
      </div>
      <div class="modal-body" style="background-color: #0a2737;">
        <form class=" g-3 " enctype="multipart/form-data"  id="editModalNurse">
            <span hidden name="patient_idEdit"><?php echo $patient_id;?></span>
            <div class="row col-8">
                <label for="inputAddressModal" class="form-label">Name</label>
                <input type="text" class="form-control readonly" id="inputAddressModal" readonly value="<?php echo $drfname; ?>">
                <input type="text" class="form-control readonly" id="inputAddressModal" readonly value="<?php echo $drlname; ?>">
            </div>
            <hr>
          <div class="row col-md-8">
            <label for="inputEmail4Modal" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail4Modal" name="inputEmail4Modal" value="<?php echo $dremail; ?>">
          </div>
          <hr>

          <div class="row  col-8 ">
            <label for="inputPhone4Modal" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="inputPhone4Modal" name="inputPhone4Modal" value="<?php echo $drphone; ?>">
          </div>
          <hr>
        
          <div class="row col-8">
            <label for="inputAddress2Modal" class="form-label">Address</label>
            <input type="text" class="form-control" id="inputAddress2Modal" name="inputAddress"  value="<?php echo $addy; ?>">
        </div>
        <hr>
        <div class="row col-md-8" id="lastcol">
            <label for="inputCityModal" class="form-label">Plan</label>
            <input type="text" class="form-control readonly" id="inputCityModal" readonly value="<?php echo $subscription; ?>">
          </div>
        
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- <h2 class="stick">
            <marquee>Welcome, Nurse <span style="color: blue;"><?php echo $name_member; ?></span></marquee>
        </h2> -->
      

<div class="content home" id="modalhome">
            <nav class="navtop" id="caldiv" >
                <div class="circle-icon">
                    <i class='bx bx-calendar'></i>
                </div>
                <div class="navborder" id='calfornav'>
                    <h2>Upcoming Appointments</h2>
                </div>
            </nav>
            <div class="calblock" >

            <div class="calbox">
                <?= $calendar ?>

            </div>
    <div id="calendarnext" data-year=<?= $calendar->active_year; ?> data-month="<?= $calendar->active_month; ?>" data-day="<?= $calendar->active_day; ?>" hidden></div>

   
</div>


   <!-- Modal -->
   <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenter" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Appointment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalbody">
      
      </div>
      <div class="modal-footer">
        <div class="container">
        <div class="row" style="padding: 5px;">
                <button type="button" class="btn btn-primary"style="width: 48%; margin: 2px;" data-bs-dismiss="modal" id="modalbtn"><i class='bx bxs-edit' style="margin-right: 5px;"></i>Edit Appointment</button>
                <button type="button" class="btn btn-primary "  style="width: 48%; margin: 2px; margin-left: 10px;" data-bs-dismiss="modal" id="modalbtn1"><i class='bx bxs-bell-ring' style="margin-right: 5px;"></i>Notify Patient</button>
      <button type="button" class="btn btn-danger btn-block mt-3" data-bs-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<nav class="navtop" id="caldiv" >
    <div class="circle-icon tube">
    <i class='bx bx-test-tube' ></i>
    </div>
    <div class="navborder" id="testblockfornav">
        <h2>Submit Tests</h2>
    </div>
</nav>
<div class="testblock">
<div>
<h1 id="titletest">Upload Test</h1>
</div>
<div class="filter-section">
            <label for="test-type">Filter by Test Type:
            <select id="test-type" name="test-type">
                <option value="" disabled selected>Select a Test Type</option>
                <?php
                    include '../admin/conn.php'; // Database connection

                    $query = "SELECT DISTINCT test_type FROM tests";
                    $test_types = [];

                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->execute();
                         $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                         echo "<option id='test-type' value=".  $row['test_type']. ">". $row['test_type'] . "</option>" ; // Store the test types in an array
                         }

                        $stmt->close();
                        } else {
                echo "Error fetching test types: " . $mysqli->error;
                    }
                    $mysqli->close();
                ?>
                
                    <option id='test-type' value="Blood Test">Blood Test</option>
                    <option id='test-type' value="Urine Test">Urine Test</option>
                    <option id='test-type' value="Stool Test">Stool Test</option>
                    <option id='test-type'value="X-Ray">X-Ray</option>
                    <option id='test-type'value="MRI">MRI</option>
                    <option id='test-type'value="CT Scan">CT Scan</option>
                    <option id='test-type'value="Ultrasound">Ultrasound</option>
                    <option id='test-type'value="ECG">ECG</option>
                    <option id='test-type'value="Echocardiogram">Echocardiogram</option>
                    <option id='test-type'value="Blood Sugar Test">Blood Sugar Test</option>
                    <option id='test-type'value="Liver Function Test">Liver Function Test (LFT)</option>
                    <option id='test-type'value="Kidney Function Test">Kidney Function Test (KFT)</option>
                    <option id='test-type'value="Thyroid Function Test">Thyroid Function Test (TFT)</option>
                    <option id='test-type'value="Lipid Profile">Lipid Profile</option>
                    <option id='test-type'value="HIV Test">HIV Test</option>
                    <option id='test-type'value="Pregnancy Test">Pregnancy Test</option>
                    <option value="Other">Other</option>
            </select>
            </label>
            <div class="date-range-group">
                <label for="appt"  id="apptlabel" style="max-width:70%;">Appointment:
                    <select name="appt" id="apptTest" style="max-width:80%;">
                            <?php
                                include '../admin/conn.php'; // Database connection
                                
                                $sql = sprintf(
                                    "SELECT * FROM appointment WHERE patient_id='%s' AND date > CURDATE()",
                                    $mysqli->real_escape_string($patient_id)
                                );
                                

                                if ($stmt = $mysqli->prepare($sql)) {
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($appt= $result->fetch_assoc()) {
                                    echo "<option id='testapptvalue' value=".  $appt['appointment_id']. "> ". $appt['reason'] ." - ". $appt['date']."</option><hr id='hrtests'><hr width='500px;' color='white;' size='10;'>" ; // Store the test types in an array
                                    } 

                                    $stmt->close();
                                    } else {
                            echo "Error fetching Appointments: " . $mysqli->error;
                                }
                                $mysqli->close();
                            ?>
                    </select>
                </label>

                <label for="date-range" id="datelabel">Test Date:
                    <input type="date" id="start-date" name="start-date">
                </label>
            </div>
        </div>

<div class="tests-page-container" id="upload-container" >
    <i class='bx bx-cloud-upload'></i>
    <button class="btn btn-success" id="browse-btn"> Browse From Computer</button> 
    <input type="file" id="file-input" accept=".pdf" style="display: none;">
    <p>Make sure your file is in .pdf form</p>
    <div id="file-list"></div> 
</div>
<button class='btn btn-primary submittestbtn'>Submit</button>
<!-- 
        <table class="tests-table">
      
        </table> -->
    </div>
    </div>
 
    </main>

    <footer class="footer">
        <div class="inner-footer">
            <div class="footer-items">
                <h2>MedCon</h2>
                <div class="border"></div>
                <ul>
                    <li><a href="#about">About</a></li>
                    <li><a href="/MedCon/Drs/dr_view_free.php">View Doctors</a></li>
                </ul>
            </div>
            <div class="footer-items">
                <h2>Contact Us</h2>
                <div class="border"></div>
                <ul>
                    <li><i class="fa fa-map-marker"></i> Beirut, Lebanon</li>
                    <li><i class="fa fa-phone"></i> +961 76-853649</li>
                    <li><i class="fa fa-envelope"></i> CustomerSupport@MedCon.lb</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; MedCon 2024. All rights reserved.
        </div>
    </footer>
    <script src="chat.js"></script>
    <script src="patient_noti.js"></script>
    <script src="upgrade-modal.js"></script>
    <script src="cal.js"></script>
    <script src="edit.js"></script>
    <script src="time.js"></script>
    <script src="script.js"></script>
    <script>
        $(document).ready(function(){
            $(".modal-btn-notify").hide();
        })
    </script>
  
</body>
</html>
