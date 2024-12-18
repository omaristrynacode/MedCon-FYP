<?php 
include 'calendar.php';
$calendar = new Calendar(date("Y/m/d"));
$calendar->add_event("checkup", "2024-08-31", 1);
session_start();
if (isset($_SESSION['sess_user'])) {
  $username = $_SESSION['sess_user']; // Retrieve the username
  $hosp_id = $_SESSION['hosp_id'];
  $name_member = $_SESSION['name'];
  $patient_id= $_SESSION["patient_id"];
  $email = $_SESSION["EMAIL"];
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
    
    <title>MedCon - Turning the Medical World Digitally</title>

    <link rel="stylesheet" href="patienthome.css?v=<?= rand(1, 1000) ?>">
    <link rel="stylesheet" href="patientcalender.css?v=<?= rand(1, 1000) ?>">
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">

    <!-- Scripts and Stylesheets -->
    <script src="https://kit.fontawesome.com/34652f094f.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:wght@100..700" rel="stylesheet">
</head>
<body>
    <header>
        <div class="banner">
            <nav class="nav">
                <div class="logo">
                    <h1>MedCon</h1>
                </div>
                <div class="navigation">
                    <ul>
                        <li><h4><?php echo $hospname;?></h4></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <h2 class="stick">
            <marquee>Welcome, <span style="color: blue;"><?php echo $name_member . $email?></span></marquee>
        </h2>
    <nav class="sidebar locked">
        <div class="logo_items">
            <span class="logo_name">MedCon</span>
        </div>
        <div class="menu_container">
            <div class="menu_items">
                <ul class="menu_item">
                    <li class="item">
                        <a href="/MedCon/Drs/dr_view.php" class="link flex">
                            <i class="bx bx-user"></i>
                            <span>Doctors</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="#tests" class="link flex">
                            <i class="bx bx-test-tube"></i>
                            <span>Upload Tests</span>
                        </a>
                    </li>
                    <li class="item">
                        <a href="#caldiv" class="link flex">
                            <i class="bx bx-calendar"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                    <li class="item">

                        <a href="#" class="link flex" id="editbtn" type="button" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="bx bxs-id-card"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="push">
                <div class="sidebar_profile">
                    <span class="name"><?php echo $name_member; ?></span>
                    <ul>
                        <li class="item">
                            <a href="/MedCon/index/index.php" class="link flex" id="logout">
                                <i class="bx bx-log-out"></i>
                                <span>Log Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="body">
       
    <?php 
        include '../admin/conn.php';
        $sql = sprintf("SELECT * FROM patient WHERE patient_id='%s'",
        $mysqli->real_escape_string($patient_id));
        $result =$mysqli->query($sql);
        $patres = $result->fetch_assoc();
        $pat_email = $patres["email"];
        $pat_phone = $patres["phone"];
        $pat_name = $patres["fname"] . " " . $patres["lname"];
        $pat_adr = $patres["address"];
        $pat_dob = $patres["dob"];
        $pat_dr = $patres["dr_id"];
        $pat_nrs = $patres["nurse_id"];
        if($pat_dr > 0){
            $sql = sprintf("SELECT * FROM doctor WHERE dr_id='%s'",$mysqli->real_escape_string($pat_dr));
            $result =$mysqli->query($sql);
            $docres = $result->fetch_assoc();
            
            $drname = $docres["fname"] . " " . $docres["lname"];
        }else{
            $drname = "No Doctor Assigned Yet.";
        }
        $_SESSION['patient_id'] = $patient_id;
            ?>
        <div class="row">
            <div class="col-md-4">
                <img src="/MedCon/img/lady2.jpg" alt="Image" class="img-thumbnail">
            </div>
            <div class="col-md-7">
                <form class="row g-3 ms-3">
                    <div class="col-md-6">
                        <label for="inputEmail4" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail4" placeholder="<?php echo $pat_email; ?>" disabled readonly>
                        
                    </div>
                    <div class="col-4 ms-3">
                    <label for="inputEmail4" class="form-label">Phone</label>
                    <input type="tel" class="form-control" id="inputPhone4" placeholder="<?php echo $pat_phone; ?>" disabled readonly>
                    </div>
                    <div class="col-6">
                        <label for="inputAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="<?php echo $pat_adr; ?>" disabled readonly>
                    </div>
                    <div class="col-4 ms-3">
                        <label for="inputAddress2" class="form-label">Hospital</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="<?php echo $hospname; ?>" disabled readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="inputCity" class="form-label">Doctor</label>
                        <input type="text" class="form-control" id="inputCity" placeholder="<?php echo $drname; ?>" disabled readonly>
                    </div>
                    <div class="col-md-4 ms-3">
                        <label for="inputZip" class="form-label">Date of Birth</label>
                        <input type="text" class="form-control" id="inputZip" placeholder="<?php echo $pat_dob; ?>" disabled readonly>
                    </div>
                    <div class="d-grid gap-2 col-6 md-auto">
</div>
         </form>
            </div>
        </div>

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3 ms-3">
          <div class="col-md-6">
            <label for="inputEmail4Modal" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail4Modal" placeholder="omar.saady@outlook.com">
          </div>
          <div class="col-4 ms-3">
            <label for="inputPhone4Modal" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="inputPhone4Modal" placeholder="76853649">
          </div>
          <div class="col-6">
            <label for="inputAddressModal" class="form-label">Address</label>
            <input type="text" class="form-control" id="inputAddressModal" placeholder="1234 Main St">
          </div>
          <div class="col-4 ms-3">
            <label for="inputAddress2Modal" class="form-label">Hospital</label>
            <input type="text" class="form-control" id="inputAddress2Modal" placeholder="AUBMC">
          </div>
          <div class="col-md-6">
            <label for="inputCityModal" class="form-label">City</label>
            <input type="text" class="form-control" id="inputCityModal" placeholder="Bchamoun">
          </div>
          <div class="col-md-4 ms-3">
            <label for="inputZipModal" class="form-label">Gender</label>
            <select class="form-select" id="inputZipModal">
              <option selected>Male</option>
              <option>Female</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="inputImageModal" class="form-label">Image</label>
            <input type="file" class="form-control" id="inputImageModal">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
        <nav class="navtop" id="caldiv" >
            <h2>Upcoming Appointments:</h2>
        </nav>

        <div class="content home" >
    <?= $calendar ?>
    <form id="appointment-form">
  <h2 style="color: blue; text-align: center;">Request an Appointment:</h2>
  <div class="rowd" style="margin-top: 50px; margin-bottom:30px;">
    <div class="col-md-6">
      <div class="form-group">
        <label for="reason">Reason for Appointment:</label>
        <textarea class="form-control" id="reason" rows="3"></textarea>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="doctor">Choose a Doctor:</label>
        <select class="form-select" id="doctor">
          <?php
          // assume you have a list of doctors in an array $doctors
          foreach ($doctors as $doctor) {
            echo "<option value='$doctor[id]'>$doctor[name]</option>";
          }
          ?>
        </select>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Request Appointment</button>
</form>
</div>
        <nav class="navtop" id="tests">
    <h2>Upload Tests:</h2>
</nav>
<div class="row">
    <div class="col-md-6">
        <form>
            <div class="mb-3">
                <label for="testName" class="form-label">Test Name:</label>
                <input type="text" class="form-control" id="testName" placeholder="Enter test name">
            </div>
            <div class="mb-3">
                <label for="testFile" class="form-label">Test File:</label>
                <input type="file" class="form-control" id="testFile">
            </div>
            <div class="mb-3">
                <label for="testDescription" class="form-label">Test Description:</label>
                <textarea class="form-control" id="testDescription" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Upload Test</button>
        </form>
    </div>
    <div class="col-md-5 ms-auto">
        <div class="card-wrapper">
        <div class="card text-right" style="height: 300px; overflow-y: scroll;">
            <div class="card-header">Previous Uploads:</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Test 1</li>
                    <li class="list-group-item">Test 2</li>
                    <li class="list-group-item">Test 3</li>
                    <li class="list-group-item">Test 4</li>
                    <li class="list-group-item">Test 5</li>
                    <li class="list-group-item">Test 1</li>
                    <li class="list-group-item">Test 2</li>
                    <li class="list-group-item">Test 3</li>
                    <li class="list-group-item">Test 4</li>
                    <li class="list-group-item">Test 5</li>
                    <!-- Add more list items here -->
                </ul>
            </div>
        </div>
        </div>
    </div>
</div>
<nav class="navtop">
    <h2>MedChat</h2>
</nav>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="chat-container">
                <div id="chat-log" style="height: 300px; overflow-y: scroll;">
                    <!-- Chat log will be displayed here -->
                </div>
                <div id="chat-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="chat-input" placeholder="Type a message...">
                        <button class="btn btn-primary" id="send-btn">Send</button>
                    </div>
</div>
            </div>
        </div>
    </div>
</div>
    </main>

    <div class="footer">
		<div class="inner-footer">
			<div class="footer-items">
				<h2>MedCon</h2>
				<div class="border"></div>
				<ul>
					<a href="/MedCon/loggingin/signin.php"><li>Sign In</li></a>
					<a href="#about"><li>About</li></a>
					<a href="/MedCon/Drs/dr_view_free.php"> <li>View Doctors</li></a>
				</ul>
			</div>

			<div class="footer-items">
				<h2>Contact Us</h2>
				<div class="border"></div>
				<ul>
					<li><i class="fa fa-map-marker" ></i> Beirut, Lebanon</li>
					<li><i class="fa fa-phone" ></i>+961 76-853649</li>
					<li><i class="fa fa-envelope" ></i>CustomerSupport@MedCon.lb</li>
				</ul>
      
		</div>  
	</div>
  <div class="footer-bottom">
    &copy; MedCon 2024. All rights reserved.
 </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="chat.js"></script>

</body>
</html>
