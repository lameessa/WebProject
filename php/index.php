<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Rifq | Home</title>
	<link rel="stylesheet" href="../css/HFstyle.css">
	<link rel="stylesheet" href="../css/indexStyle.css">
    </head>
    <body>
	<!-- Header -->
	<header>
		<div class="container">
			<div class="logo">
				<a href="index.php"><img src="../images/logo.png" alt="Rifq Logo"><span id="rifq">Rifq</span><span id="clinic">Clinic</span></a>
			</div>
			<div class="header-button">
				<button id="login-signup-button"><img src="../images/login-header.png" alt="Log in or Sign up"></button>
			</div>
		</div>
	</header>
		
		
	<!--main start-->
	<main>	
		<!--welcome-here start -->
		<section id="home" class="welcome-here">
			<div class="container">
				<div class="welcome-here-txt">
					<p>Welcome to Rifq Veterinary Clinic! </p>
				</div>
				<h2>Expert care for your <br> pets at <span>Rifq Clinic.</span></h2>
				<a href="#login-section" id="appointment-button"><img src="../images/home-book-paw.png" alt="paws">Book Appointment</a>
			</div>
		</section><!--/.welcome-here-->
		<!--welcome-here end -->

		<!--list-topics start -->
		<section id="list-topics" class="list-topics">
			<div class="container">
				<div class="list-topics-content">
					<ul>
						<li>
							<div class="single-list-topics-content">
								<div class="single-list-topics-icon">
									<img src="../images/vet-home.png" alt="veterinary house">
								</div>
								<div class="single-list-topics-text">
									<h2>Veterinary Care</h2>
									<p>Compassionate pet health and wellness — always one step ahead.</p>
								</div>
							</div>
						</li>
						<li>
							<div class="single-list-topics-content">
								<div class="single-list-topics-icon">
									<img src="../images/doctor-home.png" alt="veterinary house">
								</div>
								<div class="single-list-topics-text">
									<h2>Expert Doctors</h2>
									<p>Our clinic is home to the most skilled and compassionate veterinarians.</p>
								</div>
							</div>
						</li>
						<li>
							<div class="single-list-topics-content">
								<div class="single-list-topics-icon">
									<img src="../images/med-home.png" alt="veterinary house">
								</div>
								<div class="single-list-topics-text">
									<h2>Prescribed Medications</h2>
									<p>We offer reliable treatments tailored to your pet's needs.</p>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</div><!--/.container-->

		</section><!--/.list-topics-->
		<!--list-topics end-->
		
		<!--Log in split start-->
		<section class="split-section" id="login-section">
			<!-- Left Half: Image -->
			<div class="left-half">
				<img src="../images/home-dog.png" alt="Dog">
			</div>
			<!-- Right Half: Login Content -->
			<div class="right-half">
				<h2>Welcome to Your Pet's<br>Health Portal!</h2>
				<p>Log in to access your account and view your pet's <br> medical history, upcoming appointments, and personalized care recommendations.</p>
				<button class="login-button">Login</button>
				<a href="Signup.php" class="signup-link">Don't have an account? Sign up here.</a>
			</div>
		</section>
		<!--Log in split end-->

	</main>
	<!--main end-->
		
	<!-- Footer Area -->
	<footer id="footer" class="footer ">
		<!-- Footer Top -->
		<div class="footer-top">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="single-footer">
							<h2>About Us</h2>
							<p>At Rifq Clinic, we are dedicated to providing exceptional veterinary care for your beloved pets.
							   Our team of experienced professionals is committed to ensuring the health and well-being of your furry
							   companions through personalized treatment plans and compassionate service.
							</p>
						</div>
					</div>
					<div class="col">
						<div class="single-footer">
							<h2>Open Hours</h2>
							<p>Below are our operating hours:</p>
							<ul class="time-sidual">
								<li class="day">Monday - Thursday <span>9:00 AM - 3:00 PM</span></li>
								<li class="day">Friday <span>8:00 AM - 8:00 PM</span></li>
								<li class="day">Saturday <span>9:00 AM - 6:30 PM</span></li>
							</ul>
						</div>
					</div>
					<div class="col">
						<h2>Contact Us</h2>
						<ul class="social">
							<li><i class="icofont-facebook"><img src="../images/facebook-icon.png" alt="facebook">@Rifq_Clinic</i></li>
							<li><i class="icofont-x"><img src="../images/x-icon.png" alt="x">@Rifq_Clinic</i></li>
							<li><i class="icofont-instagram"><img src="../images/instagram-icon.png" alt="instagram">@Rifq_Clinic</i></li>
							<li><i class="icofont-gmail"><img src="../images/gmail-icon.png" alt="gmail">Rifq_Clinic@gmail.com</i></li>
							<li><i class="icofont-phone"><img src="../images/phone-icon.png" alt="phone">+966 555 123 456</i></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!--/ End Footer Top -->
		<!-- Copyright -->
		<div class="copyright">
			<div class="container">
				<div class="row">
					<div class="copyright-content">
						<p>© Copyright 2025  |  All Rights Reserved by <span>IT329</span> </p>
					</div>
				</div>
			</div>
		</div>
		<!--/ End Copyright -->
	</footer>
	<!--/ End Footer Area -->
		
	<script src="../js/index.js"></script>
		
    </body>
</html>