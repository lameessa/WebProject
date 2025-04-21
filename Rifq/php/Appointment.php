<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();
include 'AuthCheck.php';

if ($_SESSION['user_type'] !== 'patient') {
    header("Location: index.php");
    exit();
}

$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$patient_id = $_SESSION['user_id'];

$specialties = [];
$doctors = [];

$sql = "SELECT id, speciality FROM Speciality";
$result = $connection->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $specialties[] = $row;
    }
}

$sql = "SELECT id, firstName, lastName FROM Doctor";
$result = $connection->query($sql);
if ($result && $result->num_rows > 0) {
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
    <link rel="stylesheet" href="../css/HFstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php"><img src="../images/logo.png" alt="Rifq Logo"><span id="rifq">Rifq</span><span id="clinic">Clinic</span></a>
            </div>
            <div class="header-button">
                <a href="Logout.php"><img src="../images/LogOut.png" alt="Log out"></a>
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
                <form id="specialty-form" class="main_form">
                    <div class="contactus">
                        <label for="specialty">Select Specialty:</label>
                        <select id="specialty" name="specialty" required>
                            <option value="">-- Select Specialty --</option>
                            <?php foreach ($specialties as $spec): ?>
                                <option value="<?= $spec['id'] ?>">
                                    <?= $spec['speciality'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <form id="appointment-form" class="main_form" method="POST" action="addAppointment.php">
                    <div class="contactus">
                        <label for="doctor">Select Doctor:</label>
                        <select id="doctor" name="doctor_id" required>
                            <option value="">Select a doctor...</option>
                            <?php
                            if (!empty($doctors)) {
                                foreach ($doctors as $doc) {
                                    $fullName = $doc['firstName'] . ' ' . $doc['lastName'];
                                    echo "<option value='{$doc['id']}'>{$fullName}</option>";
                                }
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

                    <input type="hidden" name="patient_id" value="<?php echo $_SESSION['user_id']; ?>"> 
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

<script>
$(document).ready(function() {
    $('#specialty').change(function() {
        var specialtyId = $(this).val();
        $('#doctor').html('<option value="">Loading doctors...</option>');
        
        if (specialtyId) {
            $.ajax({
                url: 'getDoctorsBySpecialty.php',
                type: 'POST',
                data: {specialty_id: specialtyId},
                dataType: 'json',
                success: function(response) {
                    $('#doctor').empty();
                    
                    if (response.error) {
                        $('#doctor').append('<option value="">Error: ' + response.error + '</option>');
                    } else if (response.message) {
                        $('#doctor').append('<option value="">' + response.message + '</option>');
                    } else {
                        $('#doctor').append('<option value="">Select a doctor...</option>');
                        $.each(response, function(key, doctor) {
                            $('#doctor').append(
                                '<option value="' + doctor.id + '">' + 
                                doctor.firstName + ' ' + doctor.lastName + 
                                '</option>'
                            );
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#doctor').empty();
                    $('#doctor').append('<option value="">Error loading doctors</option>');
                    console.error("AJAX Error:", status, error);
                    console.log("Full response:", xhr.responseText);
                }
            });
        } else {
            $('#doctor').empty();
            $('#doctor').append('<option value="">Select a doctor...</option>');
        }
    });
});
</script>
</body>
</html>




