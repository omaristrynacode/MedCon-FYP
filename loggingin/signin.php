
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="signin.css?v=<?=rand(1,1000)?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/34652f094f.js" ></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">



    
</head>
<body>
<div class="banner">
        <header>
            <nav class="nav">
                <div class="logo">
                    <h1>MedCon</h1>
                </div>
                <div class="navigation">
                    <ul>
                        <li><a href="/MedCon/index/index.php">Home</a> </li>
                        <!-- <li><a href="">Hospitals</a> </li> -->
                        <li><a href="/MedCon/index/index.php#about">About Us</a> </li>
                        <li><a href="/MedCon/index/index.php#hospitals">Hospitals</a> </li>

                    </ul>
                </div>
            </nav>
        </header>
    </div>
    <?php
$error_message = "";
if (isset($_POST["emaillog"], $_POST["passlog"])) {
    if (!empty($_POST['emaillog']) && !empty($_POST['passlog'])) {
        $user = $_POST['emaillog'];
        $pass = $_POST['passlog'];

        include '../admin/conn.php';

        // Check if the user is a superadmin
        $sql_superadmin = sprintf("SELECT * FROM superadmin WHERE email='%s' AND password='%s'",
            $mysqli->real_escape_string($user),
            $mysqli->real_escape_string($pass)
        );

        $result_superadmin = $mysqli->query($sql_superadmin);

        if (!$result_superadmin) {
            $error_message = 'Invalid query:' . $mysqli->error . "<br>";
        }

        $numrows_superadmin = $result_superadmin->num_rows;

        if ($numrows_superadmin != 0) {
            // User is a superadmin, redirect to SuperadminSignin.php
            $row_superadmin = $result_superadmin->fetch_assoc();
            $name = $row_superadmin['name'];
            session_start();
            $_SESSION['sess_user'] = $name; // Store the username in a session variable
            $_SESSION['email'] = $user; // Update session variable for email    
            $_SESSION['level'] = "superadmin";
            header("Location: ../admin/superadmin.php");
            exit();
        } else {
            // User is not a superadmin, check if they are an admin
            $sql_admin = sprintf("SELECT *, hosp_id FROM hospitaladmin WHERE email='%s' AND password='%s'",
                $mysqli->real_escape_string($user),
                $mysqli->real_escape_string($pass)
            );

            $result_admin = $mysqli->query($sql_admin);

            if (!$result_admin) {
                $error_message = 'Invalid query:' . $mysqli->error . "<br>";
            }

            $numrows_admin = $result_admin->num_rows;

            if ($numrows_admin != 0) {
                // User is an admin, redirect to AdminSignin.php
                $row_admin = $result_admin->fetch_assoc();
                $hosp_id = $row_admin['hosp_id'];
                $name = $row_admin['name'];
                session_start();
                $_SESSION['sess_user'] = $name;
                $_SESSION['email'] = $user;
                $_SESSION['level'] = "admin";
                $_SESSION['hosp_id'] = $hosp_id;
                header("Location: ../admin/adminpage.php");
                exit();
            } else {
                // Check if the user is a doctor
                $sql_member = sprintf("SELECT * FROM doctor WHERE email='%s' AND password='%s'",
                    $mysqli->real_escape_string($user),
                    $mysqli->real_escape_string($pass)
                );

                $result_member = $mysqli->query($sql_member);

                if (!$result_member) {
                    $error_message = 'Invalid query:' . $mysqli->error . "<br>";
                }

                $numrows_member = $result_member->num_rows;

                if ($numrows_member != 0) {
                    $row_dr = $result_member->fetch_assoc();
                    $hosp_id_member = $row_dr['hosp_id'];
                    $name_member = $row_dr["fname"] . " " . $row_dr["lname"];
                    $dr_id = $row_dr["dr_id"];
                    session_start();
                    $_SESSION['sess_user'] = $user;
                    $_SESSION['name'] = $name_member;
                    $_SESSION['level'] = "member";
                    $_SESSION["dr_id"] = $dr_id;
                    $_SESSION['hosp_id'] = $hosp_id_member;
                    header("Location: ../Dr_side/dr_home.php");
                    exit();
                } else {
                    // Check if the user is a nurse
                    $sql_nurse = sprintf("SELECT * FROM nurse WHERE email='%s' AND password='%s'",
                        $mysqli->real_escape_string($user),
                        $mysqli->real_escape_string($pass)
                    );

                    $result_nurse = $mysqli->query($sql_nurse);

                    if (!$result_nurse) {
                        $error_message = 'Invalid query:' . $mysqli->error . "<br>";
                    }

                    $numrows_nurse = $result_nurse->num_rows;

                    if ($numrows_nurse != 0) {
                        $row_nurse = $result_nurse->fetch_assoc();
                        $hosp_id_nurse = $row_nurse['hosp_id'];
                        $name_nurse = $row_nurse["fname"] . " " . $row_nurse["lname"];
                        $nurse_id = $row_nurse["nurse_id"];
                        $assigned_to = $row_nurse["assigned_dr"];
                        session_start();
                        $_SESSION['assigned_dr'] = $assigned_to;
                        $_SESSION['sess_user'] = $user;
                        $_SESSION['name'] = $name_nurse;
                        $_SESSION['level'] = "nurse";
                        $_SESSION["nurse_id"] = $nurse_id;
                        $_SESSION['hosp_id'] = $hosp_id_nurse;
                        header("Location: ../nurse/nursepage.php");
                        exit();
                    } else {
                        // Check if the user is a patient (generic user)
                            $sql_user = sprintf("SELECT * FROM patient WHERE email='%s'",
                            $mysqli->real_escape_string($user)
                            );

                            // Log the query for debugging
                            error_log("SQL Query: " . $sql_user);

                            $result_user = $mysqli->query($sql_user);

                            if (!$result_user) {
                            $error_message = 'Invalid query: ' . $mysqli->error . "<br>";
                            error_log("Query Error: " . $mysqli->error); // Log any query error
                            echo $error_message; // Output error to screen for debugging
                            }

                            $numrows_user = $result_user->num_rows;

                            // Debugging: Log number of rows returned
                            error_log("Number of rows: " . $numrows_user);

                            if ($numrows_user != 0) {
                            $row_user = $result_user->fetch_assoc();
                            $patient_id = $row_user['patient_id'];
                            $name_user = $row_user["fname"] . " " . $row_user["lname"];

                            // Debugging: Log user details
                            error_log("User Found: ID - $patient_id, Name - $name_user");
                            error_log(password_verify($pass, $row_user['password']));
                            // Check if the provided password matches the hashed password stored in the database
                            if (password_verify($pass, $row_user['password'])) {
                                // Debugging: Log successful password verification
                                error_log("Password verification successful for user: $user");

                                session_start();
                                $_SESSION['sess_user'] = $user;
                                $_SESSION['name'] = $name_user;
                                $_SESSION['level'] = "patient";
                                $_SESSION["patient_id"] = $patient_id;

                                // Redirect to the appropriate page based on the payment status
                                if ($row_user['paid'] == "paid") {
                                    error_log("User is paid, redirecting to patient_home_paid.php");
                                    header("Location: ../patient/patient_home_paid.php");
                                } else {
                                    error_log("User is not paid, redirecting to patient_home_free.php");
                                    header("Location: ../patient/patient_home_free.php");
                                }
                                exit();
                            } else {
                                // Debugging: Log failed password verification
                                error_log("Password verification failed for user: $user");
                                $error_message = "Invalid email or password";
                                echo $error_message; // Output error to screen for debugging
                            }
                            } else {
                            // Debugging: Log no user found
                            error_log("No user found with email: $user");
                            $error_message = "Invalid email or password";
                            echo $error_message; // Output error to screen for debugging
                            }

                    }
                }
            }
        }
    } else {
        $error_message = "Please enter both email and password";
    }
}
?>

    <div class="login-page" id="login">
        <div class="form" >
       
            <form class="login-form"  method="POST">
                <h1 id="signinh2">Sign In</h1>
                <div class="icon-holder">
                    <span>
                        <i class='bx bxl-facebook bx-sm bx-border-circle'></i>
                    </span>
                    <span>
                        <i class='bx bxl-google bx-sm bx-border-circle'></i>
                    </span>
                    <span>
                        <i class='bx bxl-microsoft bx-sm bx-border-circle' ></i>
                    </span>
                </div>
                <input type="text" placeholder="Email" id="name_log" name="emaillog" required/>
                <input type="password" placeholder="Password" id="pass_log" name="passlog" required/>
                <button>Sign In</button>
                <span class="error" hidden>Enter Fields</span>
                <div id="php-error" data-message="<?php echo htmlspecialchars($error_message); ?>" style="display:none;"></div>

                <p class="message">Not registered? <a href="#">Create an account</a></p>
            </form>
            <div class="rightside">
                
            </div>
        </div>
    </div>
    <div class="login-page" id="regform" hidden>
        <div class="form" >
            <form class="register-form" action="./signup.php" method="POST" >
            <h1 id="signinh2">Sign Up</h1>
                <div class="icon-holder">
                    <span>
                        <i class='bx bxl-facebook bx-sm bx-border-circle'></i>
                    </span>
                    <span>
                        <i class='bx bxl-google bx-sm bx-border-circle'></i>
                    </span>
                    <span>
                        <i class='bx bxl-microsoft bx-sm bx-border-circle' ></i>
                    </span>
                </div>
                <input type="text" placeholder="First Name" name="first_name_reg" id="first_name_reg" required/>
                <input type="text" placeholder="Last Name" name="last_name_reg" id="last_name_reg" required/>
                <input type="tel" placeholder="Phone" name="phone_reg" id="phone_reg" required/>
                <input type="email" placeholder="Email@example.com" name="email_reg" id="email_reg" required/>
                <input type="password" placeholder="Password" name="pass_reg" id="pass_reg" required/>
                <input type="text" placeholder="Address" name="address_reg" id="address_reg" required/>
                <input type="date" placeholder="Date of Birth" name="dob_reg" id="dob_reg" required/>
                <select name="gender_reg" id="gender_reg" placeholder="Gender" required>
                    <option value="1">Male</option>
                    <option value="0">Female</option>
                </select>
                <button>create</button>
                <span class="error_reg" hidden>Enter Fields</span>
                <p class="message">Already registered? <a href="#">Sign In</a></p>
            </form>
           
        </div>
    </div>
   
    <div class="footer">
		<div class="inner-footer">
			<div class="footer-items">
				<h2>MedCon</h2>
				<div class="border"></div>
				<ul>
					<a href=""><li>Sign In</li></a>
					<a href=""><li>About</li></a>
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
 <script src="signin.js"></script>
    <?php
    // footer.php
    ?>
</body>
</html>