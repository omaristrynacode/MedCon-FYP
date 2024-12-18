<?php
session_start(); // Start session to manage user information

include "../admin/conn.php";


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $billing_name = $_POST['billing_name'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    $user_id = $_SESSION['patient_id']; // Assuming the user ID is stored in session
    echo "<pre>";
    print_r($user_id);
    echo "</pre>";
    // Perform input validation
    if (!empty($billing_name) && !empty($card_number) && !empty($expiry_date) && !empty($cvv)) {
        // Update user status to "paid"
        $updateQuery = "UPDATE patient SET paid='paid' WHERE patient_id='$user_id'";
        if ($mysqli->query($updateQuery) === TRUE) {
            // Redirect to paid user dashboard
            header("Location: patient_home_paid.php");
            exit();
        } else {
            $error = "Error updating record: " . $mysqli->error;
        }
    } else {
        $error = "Please fill in all the billing information.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="../img/logo3.png" size="32x32" type="image/x-icon">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>

    <title>Upgrade to Premium</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #111;
            color: #fff;
        }

        /* Centered container */
        .container {
            width: 50%;
            margin: 60px auto;
            padding: 20px;
            background-color: #1b1e29;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #5de0e6;
        }

        form {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #aaa;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            width: 95%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }

        button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #5de0e6;
            color: #1b1e29;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #4c84ff;
            color: #fff;
        }

        .error {
            color: red;
            margin-top: 15px;
            text-align: center;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #aaa;
        }
        /* CSS for the Loading Spinner */
#loadingIndicator {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-size: 20px;
}

.spinner {
  border: 4px solid #f3f3f3; /* Light grey */
  border-top: 4px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Upgrade to Premium</h1>
        <p style="text-align: center;">Unlock premium features for only $29.99/month</p>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form action="subscribe.php" id="upgradeForm" method="POST">
            <div class="form-group">
                <label for="billing_name">Billing Name</label>
                <input type="text" name="billing_name" id="billing_name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="card_number">Card Number</label>
                <input type="text" name="card_number" id="card_number" placeholder="1234 5678 9012 3456" maxlength="16" required>
            </div>

            <div class="form-group">
                <label for="expiry_date">Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry_date" required>
            </div>

            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="number" name="cvv" id="cvv" placeholder="123" maxlength="3" required>
            </div>

            <button type="submit" id="upgradeNowBtn" class="btn-upgrade">Upgrade Now</button>
            <!-- Loading Indicator (Hidden by default) -->
<div id="loadingIndicator" style="display: none;">
  <p>Processing...</p>
  <div class="spinner"></div>
</div>

        </form>

        <div class="footer">
            <p>Please note that upon upgrading your plan, you are automatically agreeing to our terms and conditions</p>
        </div>
    </div>
</body>
<script>$(document).ready(function() {
  // When the user clicks on the "Upgrade Now" button
  $('#upgradeNowBtn').on('click', function(e) {
    e.preventDefault();  // Prevent the form from submitting immediately

    // Show the "Processing" modal
    $('#loadingIndicator').show();

    // Simulate a 3-second delay (could be the time taken to process the payment, etc.)
    setTimeout(function() {
      // Here you would normally send the form data to the server using AJAX or submit the form
      $('#upgradeForm').submit(); // Submit the form after the delay
    }, 3000);  // 3 seconds delay
  });
});
</script>
</html>
