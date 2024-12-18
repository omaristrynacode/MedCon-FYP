<?php session_start();

include 'Dr_calendar.php';
$calendar = new Calendar(date("Y/m/d"));


if (isset($_SESSION['sess_user'])) {
  $username = $_SESSION['sess_user']; // Retrieve the username
  $hosp_id = $_SESSION['hosp_id'];
  $name_member = $_SESSION['name'];
$dr_id = $_SESSION["dr_id"];
  if($hosp_id == 1){
      $hospname = "American University of Beirut Medical Center";
  }elseif($hosp_id == 2){
      $hospname = "Rafik Hariri University Hospital";
  }elseif($hosp_id == 3){
      $hospname = "Hammoud Hospital University Medical Center";
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

    <title>MedCon - Turning the Medical World Digitally</title>

    <link rel="stylesheet" href="dr_home.css?v=<?= rand(1, 1000) ?>">
    <link rel="stylesheet" href="patientcalender.css?v=<?= rand(1, 1000) ?>">
<link rel="icon" href="../img/logo3.png" size="32x32" type="image/x-icon">


    <!-- Scripts and Stylesheets -->
    <script src="https://kit.fontawesome.com/34652f094f.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@100..700" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
                            <a href="#" id="profilemodal" data-bs-toggle="modal" data-bs-target="#editModal">Profile</a>
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
                <p>Welcome back, Doctor <span><?php echo $name_member; ?></span></p>
                <p id="hosp"></p>
            </div>
        </div>
                    <?php 
                include '../admin/conn.php';
                $sql = sprintf("SELECT * FROM doctor WHERE dr_id='%s'",
                $mysqli->real_escape_string($dr_id));
                $result =$mysqli->query($sql);
                $drres = $result->fetch_assoc();
                $dremail = $drres["email"];
                $drphone = $drres["phone"];
               
                $assigneddr = $drres["fname"] . " " . $drres["lname"];
                $drfname = $drres["fname"];
                $drlname = $drres["lname"];
         
                $specialty = $drres["specialty"];
                $rating= $drres["rating"];
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
            <label for="inputAddress2Modal" class="form-label">Specialty</label>
            <input type="text" class="form-control readonly" id="inputAddress2Modal" readonly value="<?php echo $specialty; ?>">
        </div>
        <hr>
        <div class="row col-md-8" id="lastcol">
            <label for="inputCityModal" class="form-label">Rating</label>
            <input type="text" class="form-control readonly" id="inputCityModal" readonly value="<?php echo $rating; ?>">
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
    <?= $calendar ?>
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
        <h2>View Tests</h2>
    </div>
</nav>
<div class="testblock">
<div>
    <div>
        <form>
            <div class="form-group-inline" >
                <label for="searchPatient" id="searchlabel"><i class='bx bx-search'></i></label>
                <input type="text" class="form-control" id="searchPatient" placeholder="Search...">
            </div>
        </form>
    </div>
</div>


<div class="tests-page-container">
        <div class="filter-section">
            <label for="test-type">Filter by Test Type:
            <select id="test-type" name="test-type">
                <option value="all">All</option>
                <?php
                    include '../admin/conn.php'; // Database connection

                    $query = "SELECT DISTINCT test_type FROM tests";
                    $test_types = [];

                    if ($stmt = $mysqli->prepare($query)) {
                        $stmt->execute();
                         $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                         echo "<option value=".  $row['test_type']. ">". $row['test_type'] . "</option>" ; // Store the test types in an array
                         }

                        $stmt->close();
                        } else {
                echo "Error fetching test types: " . $mysqli->error;
                    }
                    $mysqli->close();
                ?>
            </select>
            </label>
            <div class="date-range-group">
            <label for="date-range">Date Range:
            <input type="date" id="start-date" name="start-date">
            <input type="date" id="end-date" name="end-date">
            </label>
            </div>
        </div>

        <table class="tests-table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Test Type</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="tests">
      
            </tbody>
        </table>
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
                    <li><a href="/MedCon/Drs/dr_view.php">View Doctors</a></li>
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
    
   <script src="edit.js"></script>
    <script src="cal.js"></script>
    <script src="time.js"></script>
    <script src="dr_noti.js"></script>
    <script >$(document).ready(function(){
    fetchTests(); 
    $("#searchPatient").on("keyup", function(){
        var search_val = $(this).val();
        fetchTests(search_val); 
    });

    $("#test-type").on("change", function() {
        fetchTests(); 
    });

    $("#start-date, #end-date").on("change", function() {
        fetchTests(); 
    });

    function fetchTests(search_val = '') {
        var test_type = $("#test-type").val();

        var start_date = $("#start-date").val();
        var end_date = $("#end-date").val();

        $.ajax({
            type: 'POST',
            url: 'search_test.php',
            data: {
                search: search_val,
                dr_id: '<?php echo $dr_id; ?>',
                test_type: test_type, // Send the selected test type
                start_date: start_date, // Send the start date
                end_date: end_date // Send the end date
            },
            success: function(response) {
                $('.tests').html(response);
            }
        });
    }
});
</script>


</body>
</html>
