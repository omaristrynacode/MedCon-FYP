<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css?v=<?=rand(1,1000)?>">
    <script src="https://kit.fontawesome.com/34652f094f.js" ></script>
    <link rel="icon" href="/MedCon/img/logo3.png" sizes="32x32" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">



    <title>MedCon - Turning the Medical World Digitally</title>
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
                        <li><a href="/MedCon/loggingin/signin.php">Sign In</a> </li>
                        <li><a href="#hospitals">Hospitals</a> </li>
                        <li><a href="#about">About Us</a> </li>
                        <li><a href="">Contact</a> </li>

                    </ul>
                </div>
            </nav>
        </header>
        
        <div class="box">
            <h1>Welcome to your best medical companion.</h1>
            <h1>Welcome to <span>MedCon.</span></h1>
              <!-- <h1>turning the medcine world into a digital one... </h1>   -->
               
              <h3>Combining the digital and medical advantages into one, MedCon helps patients & medical workers with enhanced easier communication, tracking tests and medication, health monitoring, and oversee treatment plans</h3>
              <div class="icons">
              <a href="../loggingin/signin.php"><button class="btn btn-primary" >Improve your Medical World</button></a>
              </div>
              <div class="heading">
            <h1>Hospitals</h1>
            <p>We are collaborating with </p>
        </div>
        </div>
    </div>

    <div class="info" id="hospitals">
        <div class="one">
            <div class="upper">
                
              <img src="/MedCon/img/RHUH2.jpg" alt="">
                <h4>RHUH</h4>
                <p>Rafik Hariri University Hospital Beirut Governmental University Hospital.</p>
            </div>
          

        </div>
        <div class="two">
            <div class="upper">
            <img src="/MedCon/img/aubmc.jpg" alt="">
            <h4>AUBMC</h4>
            <p style='font-size: 20px;'>American University of Beirut Medical Center.</p>

        </div>
    </div>
        <div class="three">
            <div class="upper">
            <img src="/MedCon/img/hhmuc.jpg" alt="">
            <h4>HHUMC
            </h4>
            <p>	Hammoud Hospital University Medical Center</p>
        </div>
        </div>
    </div>


    <!--about Us is start from here-->
    <div class="about" id="about">
    <div class="curtain">
        <h1>About MedCon</h1>
    </div>
</div>

<section class="details">
    <div class="details__container">
        <div class="details__img">
            <img src="/MedCon/img/phonedr.jpg" alt="Doctor on phone">
        </div>
        <div class="details__text">
    <div class="text__container">
        <div class="text__left">
            <p>
                <b class="bold">MedCon</b>, your trusted partner in healthcare management. Our platform is designed to streamline and enhance the interactions between patients, doctors, and nurses, ensuring that quality care is always just a click away.
            </p>
        </div>
        <div class="separator"></div>
        <div class="text__right">
            <p>
                At <b class="bold">MedCon</b> we believe in the power of technology to make healthcare more accessible, efficient, and personalized. Whether you're scheduling an appointment, accessing medical records, or managing your hospital's operations, our user-friendly interface and comprehensive tools are here to support you every step of the way.
            </p>
        </div>
    </div>
    <br>
    <!-- <a href='/MedCon/loggingin/signin.php' class="join_us_now">Join us on our mission to revolutionize healthcare—because your health is our top priority</a> -->
</div>

    </div>
</section>

    <div class="department">
        <div class="introduction">
        <h1 class="slide-up">Services</h1>
        <p class="slide-up">READ more about what we can offer you</p>
            <div class="department__box">
                <div class="ophtthalmology">
                    <i class="fas fa-eye fa-8x fa2"></i>
                    
                    <h3>Keeping Track</h3>
                    <p>Help you keep track of your medical tests reminding you to perfrom your scheduled tests.</p>
                </div>
                <div class="laboratory">
                    <i class="fas fa-comments fa-8x fa2"></i>

                    <h3>Enhancing Communication</h3>
                    <p>We provide a ecure platform for our pateints to communicate with their healthcare providers, facilitating better care coordination.</p>

                    
                </div>
                <div class="cardiology">
                    <i class="fas fa-heartbeat fa-8x fa2"></i>
                    <h3>Health Monitoring</h3>
                    <p>Allow you to easily manage and monitor your own health analytics.</p>
                </div>
                <div class="pediatrics">
                    <i class="fas fa-user-shield fa-8x fa2"></i>  
                    <h3>Security and Privacy</h3>
                    <p>Protecting your information through robust security measures, ensuring compliance with healthcare data protection regulations.</p>
                </div>
                <div class="dentistry">
                    <i class="fas fa-hospital-user fa-8x fa2"></i>
                    <h3>Insights to Your Healthcare Provider</h3>
                    <p>Offer tools to your healthcare providers to help them monitor your treatment plans and adjust care as necessary based on your health analytics</p>
                </div>
                <div class="dentistry">
                    <i class="fas fa-stethoscope fa-8x fa2"></i>
                    <h3>Wide Range of Doctors in the Country</h3>
                    <p>No matter where you are there is garantueed a doctor near you, in a hospital near you. Allowing for quick accessible, and comfortable visits at your need.</p>
                </div>
            </div>
        </div>
        
    </div>
    <div class="dr">
        <div class="dr__left">
            <div>
                <h1>Meet Our Doctors</h1>
                <h3>Your health is in trusted hands. Meet our dedicated team of Doctors, each commited to provide expceptional care.</h3>
                <p>Click below to learn more about their specialties, expertise, and to explore the profiles of our talented doctors who are here to support your wellness.</p>
                <a href="/MedCon/Drs/dr_view_index.php" id="view"><button>View All Doctors</button></a>

            </div>

        </div>
        <div class="dr__right">
            <img src="/MedCon/img/lady2.jpg" alt="">
        </div>


    </div>

    <div class="counter">
        <div class="doctors" >
            <h1 id="drs" class="animate-me">120+</h1>
            <h3>Certified
            Doctors</h3>
        </div>
        <div class="servics" >
            <h1 id="srv" class="animate-me">25000+</h1>
            <h3>Successful Treatments</h3>
        </div>
        <div class="affliates" >
            <h1 id="coun" class="animate-me">6</h1>
            <h3>Different
            Countries</h3>
        </div>
        <div class="programs" >
            <h1 id="users" class="animate-me">6500+</h1>
            <h3>Loyal Users</h3>
        </div>
    </div>

    <div class="special_offer">
        <div class="heading">
            <h1>Plans</h1>
            <p>START YOUR HEALTH JOURNEY TODAY</p>
        </div>
        <div class="offer">
            <div class="one">
                <h2>TWO MONTHS</h2>
                <ul><li><h1>60$</h1></li>
                </ul>
                <ul>
                    <li>Unlimited Test Uploads</li>
                    <li>Appointment Request & Cancellation</li>
                    <li>Doctor and Hospital Request</li>
                    <li>Doctor Ratings</li>
                    <li>ChatBot AI: Powered By ChatGPT</li>
                    
                </ul>
                <button id="getnowone">Get Now</button>
                
            </div>
            <div class="two">
                <h2>1 YEAR</h2>
                <ul>
                    <li>
                        <h1>300$ - Save 60$!</h1>
                    </li>
                </ul>
                <ul>
                    <li>Consultations</li>
                    <li>Teatment Plans</li>
                    <li>Unlimited Test Uploads</li>
                    <li>Appointment Request & Cancellation</li>
                    <li>Doctor and Hospital Request</li>
                    <li>Doctor Ratings</li>
                    <li>Monthly Follow Ups</li>
                    <li>ChatBot AI: Powered By ChatGPT</li>
                </ul>
                <button class="getnow" id='getnowbtn'>Get Now</button>
            </div>
            <div class="one">
                <h2>FREE USER</h2>
                    <ul><li><h1>No Charge</h1></li>
                    </ul>
                    <ul>
                    <li>Limited Test Uploads</li>
                    <li>Recieve and View Appointments</li>
                    <li>View and Filter Doctors</li>
                        
                    </ul>
                    <button id="getnowone">Get Now</button>
                
            </div>
        </div>

    </div>

    <!--The div element for the map -->
    <div class="map">
        <div class="choices">
            <ul>
            <li><a href="" id="hhmuc">HHUMC</a></li>
            <li><a href="" id="rhuh">RHUH</a></li>
            <li><a href="" id="aubmc">AUBMC</a></li>
</ul>
        </div>
<div class="mapouter">
  <div class="gmap_canvas">
    <iframe id="aubmc" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3311.6629729368724!2d35.48352167570987!3d33.898332773215394!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151f16d7ca5c2365%3A0xd929ac8a06540191!2sAUBMC!5e0!3m2!1sen!2slb!4v1724430214050!5m2!1sen!2slb" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <iframe id="rhuh" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3313.017903384363!2d35.48875177555202!3d33.86342947322864!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151f175afa9b00b5%3A0xe9b3d02652e83b0e!2sRafik%20Hariri%20University%20Hospital!5e0!3m2!1sen!2slb!4v1724431166611!5m2!1sen!2slb" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <iframe id="hhmuc" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3324.7440121150244!2d35.37273277553869!3d33.56002677334631!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151ef03dbb6c6957%3A0x2f8d87a7a9788698!2zSEhVTUMgSGFtbW91ZCBIb3NwaXRhbCBVbml2ZXJzaXR5IE1lZGljYWwgQ2VudGVyINmF2LPYqti02YHZiSDYrdmF2YjYryDYp9mE2KzYp9mF2LnZig!5e0!3m2!1sen!2slb!4v1724431290784!5m2!1sen!2slb" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
</div>

    <div class="footer">
		<div class="inner-footer">
			<div class="footer-items">
				<h2>MedCon</h2>
				<div class="border"></div>
				<ul>
					<a href="/MedCon/loggingin/signin.php"><li>Sign In</li></a>
					<a href="#about"><li>About</li></a>
					<a href="/MedCon/Drs/dr_view_index.php"> <li>View Doctors</li></a>
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

 <script src="maps.js"></script>
</body>

</html>