<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// جلب جميع التخصصات من جدول Speciality
$specialties = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT id, speciality FROM Speciality";
    $result = $connection->query($query);
    while ($row = $result->fetch_assoc()) {
        $specialties[] = $row;
    }
}

// معالجة طلب جلب الأطباء بناءً على التخصص المختار
$doctors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === 'get_doctors') {
    header('Content-Type: application/json');

    $specialty_id = $_POST['specialty_id'];
    $stmt = $connection->prepare("SELECT id, CONCAT(firstName, ' ', lastName) AS name FROM Doctor WHERE SpecialityID = ?");
    $stmt->bind_param("i", $specialty_id);
    $stmt->execute();
    $result = $stmt->get_result();


    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
    exit;
}


// معالجة حجز الموعد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctor_id'], $_POST['date'], $_POST['time'], $_POST['reason'], $_POST['patient_id'])) {
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_POST['patient_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = 'Pending';

    $query = "INSERT INTO Appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("iissss", $patient_id, $doctor_id, $date, $time, $reason, $status);
    if ($stmt->execute()) {
        header("Location: PatientHomePage.html?message=Appointment+Booked+Successfully");
        exit;
    } else {
        echo "Error booking appointment.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Appointment</title>
    <link rel="stylesheet" href="../css/styleApoi.css">
   
</head>

<header>
    <div class="container">
        <div class="logo">
            <a href="index.html"><img src="../images/logo.png" alt="Rifq Logo"><span id="rifq">Rifq</span><span id="clinic">Clinic</span></a>
        </div>
        <div class="header-button">
            <a href="index.html"><img src="../images/LogOut.PNG" alt="Log out"></a>
        </div>
    </div>
</header>

<body>
<div class="containerr">
    <div class="hero">
        <img src="../images/do.png" alt="Dog in Hoodie">
        <h1>BOOK AN <span style="color: #eee;">Appointment</span></h1>
        <p>Compassionate care for your pet, book now.</p>
        <a href="#form-section" class="explore-btn">Book now</a>
    </div>

    <div class="categories">
        <div>Skilled Personal</div>
        <div>Best Veterinars</div>
        <div>Quality Food</div>
        <div>Pets Care 24/7</div>
    </div>

    <div id="form-section" class="stats">
        <section class="banner_main">
            <!-- Form 1: Specialty Selection -->
            <form id="specialty-form" class="main_form" method="POST">
                <div class="contactus">
                    <label for="specialty">Select Specialty:</label>
                    <select id="specialty" name="specialty_id">
                        <?php foreach ($specialties as $specialty) { ?>
                            <option value="<?php echo $specialty['id']; ?>"><?php echo ucfirst($specialty['speciality']); ?></option>
                        <?php } ?>
                    </select>
                    <button type="button" id="update-doctors">Submit</button>
                </div>
            </form>

            <!-- Form 2: Appointment Booking -->
            <form id="appointment-form" class="main_form" method="POST">
                <div class="contactus">
                    <label for="doctor">Select Doctor:</label>
                    <select id="doctor" name="doctor_id">
                        <option value="">Select Specialty first</option>
                    </select>
                </div>

                <input type="hidden" name="patient_id" value="1234"> <!-- مثال لمعرف المريض -->

                <div class="contactus">
                    <label for="date">Select Date:</label>
                    <input type="date" id="date" name="date">
                </div>

                <div class="contactus">
                    <label for="time">Select Time:</label>
                    <input type="time" id="time" name="time">
                </div>

                <div class="contactus">
                    <label for="reason">Reason for Visit:</label>
                    <textarea id="reason" name="reason" rows="4"></textarea>
                </div>

                <button type="submit" id="buttonBooking">Submit Booking</button>
            </form>
        </section>
    </div>
</div>

 <footer id="footer" class="footer ">
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

        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="copyright-content">
                        <p>© Copyright 2025  |  All Rights Reserved by <span>IT329</span> </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>


<link rel="stylesheet" href="../css/HFstyle.css">
<script src="../js/Apoi.js"></script>
</body>
</html>


