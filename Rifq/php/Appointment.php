<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();

/*
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: index.php");
    exit();
}
 */

$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// جلب التخصصات من قاعدة البيانات
$specialties = [];
$doctors = [];
$selectedSpecialtyId = "";

$sql = "SELECT id, speciality FROM Speciality";
$result = $connection->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specialties[] = $row;
    }
}

// إذا تم إرسال النموذج الأول (اختيار التخصص)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update-doctors"])) {
    $selectedSpecialtyId = $_POST["specialty"];
    $stmt = $connection->prepare("SELECT id, firstName, lastName FROM Doctor WHERE SpecialityID = ?");
    $stmt->bind_param("i", $selectedSpecialtyId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../css/styleApoi.css">
</head>
<body>
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
                <!-- النموذج الأول لاختيار التخصص -->
                <form id="specialty-form" class="main_form" method="POST" action="">
                    <div class="contactus">
                        <label for="specialty">Select Specialty:</label>
                        <select id="specialty" name="specialty" required>
                            <option value="">-- Select --</option>
                            <?php foreach ($specialties as $spec): ?>
                                <option value="<?= $spec['id'] ?>" <?= ($selectedSpecialtyId == $spec['id']) ? 'selected' : '' ?>><?= htmlspecialchars($spec['speciality']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update-doctors">Submit</button>
                    </div>
                </form>

                <!-- النموذج الثاني لحجز الموعد -->
                <form id="appointment-form" class="main_form" method="POST" action="addAppointment.php">
                    <div class="contactus">
                        <label for="doctor">Select Doctor:</label>
                        <select id="doctor" name="doctor_id" required>
                            <?php
                            if (!empty($doctors)) {
                                foreach ($doctors as $doc) {
                                    $fullName = $doc['firstName'] . ' ' . $doc['lastName'];
                                    echo "<option value='{$doc['id']}'>{$fullName}</option>";
                                }
                            } else {
                                echo '<option value="">-- Please select a specialty first --</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="contactus">
                        <label for="date">Select Date:</label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="contactus">
                        <label for="time">Select Time:</label>
                        <input type="time" id="time" name="time" required>
                    </div>

                    <div class="contactus">
                        <label for="reason">Reason for Visit:</label>
                        <textarea id="reason" name="reason" rows="4" required></textarea>
                    </div>

                    <input type="hidden" name="patient_id" value="1234"> <!-- يتم تغييره لاحقًا من الجلسة -->
                    <button type="submit" id="buttonBooking">Submit Booking</button>
                </form>
            </section>
        </div>
    </div>

    <footer id="footer" class="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="single-footer">
                            <h2>About Us</h2>
                            <p>At Rifq Clinic, we are dedicated to providing exceptional veterinary care for your beloved pets.</p>
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
                            <li><img src="../images/facebook-icon.png" alt="facebook"> @Rifq_Clinic</li>
                            <li><img src="../images/x-icon.png" alt="x"> @Rifq_Clinic</li>
                            <li><img src="../images/instagram-icon.png" alt="instagram"> @Rifq_Clinic</li>
                            <li><img src="../images/gmail-icon.png" alt="gmail"> Rifq_Clinic@gmail.com</li>
                            <li><img src="../images/phone-icon.png" alt="phone"> +966 555 123 456</li>
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
</body>
<link rel="stylesheet" href="../css/HFstyle.css">
</html>



