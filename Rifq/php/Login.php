<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $servername = "localhost";
    $username   = "root";
    $password   = "root";
    $database   = "Rifq";

    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email    = $_POST['email'];
    $pass     = $_POST['password'];
    $userType = $_POST['userType'];

    if ($userType === "doctor") {
        $sql = "SELECT id, firstName, password FROM Doctor WHERE emailAddress = ?";
    } else {
        $sql = "SELECT id, firstName, password FROM Patient WHERE emailAddress = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['user_type'] = $userType;
            $_SESSION['firstName'] = $row['firstName'];

            if ($userType === "doctor") {
                header("Location: Doctor.php");
            } else {
                header("Location: Patient.php");
            }
            exit();
        } else {
            header("Location: Login.php?error=" . urlencode("Invalid password!"));
            exit();
        }
    } else {
        header("Location: Login.php?error=" . urlencode("Email not found!"));
        exit();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/HFstyle.css">
    <link rel="stylesheet" href="../css/LoginStyle.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <a href="../php/index.php">
                <img src="../images/logo.png" alt="Rifq Logo">
                <span id="rifq">Rifq</span><span id="clinic">Clinic</span>
            </a>
        </div>
    </div>
</header>

<div class="login-container">
    <div class="login-form">
        <h2>Login</h2>
        <div id="errorMsg" style="color:red; font-weight:bold; margin-bottom:10px;"></div>

      <form action="Login.php" method="POST">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role">User Role</label>
            <select id="role" name="userType">
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
            </select>

            <button type="submit">Login</button>
        </form>
    </div>

    <div class="dog-login">
        <img src="../images/dog2.PNG" alt="">
    </div>
</div>

<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="single-footer">
                        <h2>About Us</h2>
                        <p>At Rifq Clinic, we are dedicated to providing exceptional veterinary care for your beloved pets.
                           Our team of experienced professionals is committed to ensuring the health and well-being of your furry
                           companions through personalized treatment plans and compassionate service.</p>
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
                        <li><img src="../images/facebook-icon.png" alt="facebook">@Rifq_Clinic</li>
                        <li><img src="../images/x-icon.png" alt="x">@Rifq_Clinic</li>
                        <li><img src="../images/instagram-icon.png" alt="instagram">@Rifq_Clinic</li>
                        <li><img src="../images/gmail-icon.png" alt="gmail">Rifq_Clinic@gmail.com</li>
                        <li><img src="../images/phone-icon.png" alt="phone">+966 555 123 456</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="copyright-content">
                    <p>© Copyright 2025 | All Rights Reserved by <span>IT329</span></p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
  const params = new URLSearchParams(window.location.search);
  if (params.has('error')) {
    const errorText = decodeURIComponent(params.get('error'));
    document.getElementById('errorMsg').textContent = errorText;
    history.replaceState({}, "", window.location.pathname);
  }
</script>

</body>
</html>
