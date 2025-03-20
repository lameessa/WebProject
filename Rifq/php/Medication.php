<?php
// Start session
session_start();

// Database Connection
$conn = mysqli_connect("localhost", "root", "root", "Rifq"); // Adjust as needed

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



// Check if appointment ID is provided in the URL
if (!isset($_GET['appointment_id'])) {
    die("Error: Appointment ID missing.");
}
$appointment_id = $_GET['appointment_id'];

// Retrieve patient information
$query = "SELECT p.id AS patient_id, p.firstName, p.lastName, p.Gender, 
                 TIMESTAMPDIFF(YEAR, p.DoB, CURDATE()) AS age
          FROM patient p 
          JOIN appointment a ON p.id = a.PatientID
          WHERE a.id = '$appointment_id'";

$res = mysqli_query($conn, $query);
$patient = mysqli_fetch_assoc($res);

if (!$patient) {
    die("Error: Patient not found.");
}

// Retrieve available medications
$med_query = "SELECT * FROM medication";
$med_res = mysqli_query($conn, $med_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Prescription</title>
   <link rel="stylesheet" href="../css/styleMedi.css">
   <link rel="stylesheet" href="../css/HFstyle.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.html"><img src="../images/logo.png" alt="Rifq Logo">
                <span id="rifq">Rifq</span><span id="clinic">Clinic</span></a>
            </div>
            <div class="header-button">
                <a href="index.html"><img src="../images/LogOut.PNG" alt="Log out"></a>
            </div>
        </div>
    </header>

    <div class="primary">
        <div class="title">
            <h1>Patient's Medications</h1>
            <p>Your medications are prepared with precision, making us a part of your healing journey.</p>
        </div>

        <div class="contact-section">
            <div class="contact-info"></div>

            <div class="form-container">
                <form action="process_prescription.php" method="post">
                    <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
                    <input type="hidden" name="patient_id" value="<?= $patient['patient_id']; ?>">

                    <label for="name">Patient's Name:</label>
                    <input type="text" id="name" name="name" value="<?= $patient['firstName'] . ' ' . $patient['lastName']; ?>" readonly>

                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?= $patient['age']; ?>" readonly>

                    <div class="gender-group">
                        <label>Gender:</label>
                        <label for="male">
                            <input type="radio" id="male" name="gender" value="Male" <?= ($patient['Gender'] === 'Male') ? 'checked' : ''; ?>> Male
                        </label>
                        <label for="female">
                            <input type="radio" id="female" name="gender" value="Female" <?= ($patient['Gender'] === 'Female') ? 'checked' : ''; ?>> Female
                        </label>
                    </div>

                    <div class="medications">
                        <ul>
                            <label>Medications:</label>
                            <?php while ($med = mysqli_fetch_assoc($med_res)): ?>
                                <li>
                                    <input type="checkbox" name="medications[]" value="<?= $med['id']; ?>">
                                    <label><?= $med['MedicationName']; ?></label>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <button type="submit">Submit</button>
                </form>
            </div>
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
</body>
</html>

<?php
// Close the connection
mysqli_close($conn);
?>

