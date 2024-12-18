<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Our Doctors</title>
    <link rel="stylesheet" type="text/css" href="viewdr.css?v=<?=rand(1,1000)?>">
    <script src="https://kit.fontawesome.com/34652f094f.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">
    <script src="https://kit.fontawesome.com/34652f094f.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
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
                        <li><a style="cursor: pointer;" id="dash">Dashboard</a> </li>
                        <li><a href="/MedCon/index/index.php#hospitals">Hospitals</a> </li>
                        <li><a href="/MedCon/index/index.php#about">About Us</a> </li>
                        <li><a href="">Contact</a> </li>

                    </ul>
                </div>
            </nav>
        </header>
</div>
<div class="main">
  <div class="drtable">
    <h1 class="title">Doctor's List</h1>
      <div class="search-bar" hidden>
        <input type="text" id="searchBar" class="search-bar" placeholder="Search by name, phone, email, or specialty..." />
        <i class='bx bx-search-alt-2'id="magni" ></i>
      </div>
        
        <div class="form-check form-switch d-flex justify-content-between align-items-center">
      <h4 id="titledrs" hidden>Active Doctors:</h4>
  

      <table class="table">
          <thead style="background-color: blue;">
      
              <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Specialty</th>
                  <th>Date of Start</th>
                  <th>Rating</th>
              </tr>
        </thead>
            <tbody id="doctors-list"></tbody>
            <tfoot  style="background-color: blue;"></tfoot>
      </table>
      <div id="loadingSpinner" class="spinner" style="display: none;"></div>
      <div class="pagination" id="pagination">
          <div class="pagination-cover">
          <a id="prevPage" ><<</a>
          <span id="pageNumbers"></span>
          <a id="nextPage" >>></a>
          </div>
      </div>
    </div>
</div>
</div>
<div class="footer">
		<div class="inner-footer">
			<div class="footer-items">
				<h2>MedCon</h2>
				<div class="border"></div>
				<ul>
          <a style="cursor: pointer;"><li id="dash2">Dashboard</li></a> 
          <a href="/MedCon/index/index.php"> <li>Sign Out</li></a>
				</ul>
			</div>

			<div class="footer-items">
				<h2>Contact Us</h2>
				<div class="border"></div>
				<ul>
					<li><i class="fa fa-map-marker"></i>Beirut, Lebanon</li>
					<li><i class="fa fa-phone" ></i>+961 76-853649</li>
					<li><i class="fa fa-envelope" ></i>CustomerSupport@MedCon.lb</li>
				</ul>
      
		</div>  
	</div>
  <div class="footer-bottom">
    &copy; MedCon 2024. All rights reserved.
 </div>
</div>
<script src="super.js"></script>
<script>
  $("#dash").on("click",function(){
     history_back();
    })
    $("#dash2").on("click",function(){
       history_back();
    })
    function history_back() {
       window.history.back();
        } 
</script>
 
</body>
</html>