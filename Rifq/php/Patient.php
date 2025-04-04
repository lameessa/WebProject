<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();
include 'AuthCheck.php';

/*
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: index.php");
    exit();
}
 */

// الاتصال بقاعدة البيانات
$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$patient_id = 1234;;

// جلب بيانات المريض
$query = "SELECT firstName, lastName, emailAddress, DoB, Gender, id FROM Patient WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
if (!$patient) {
    die("<h2>Error: Patient not found</h2>");
}

// جلب جميع المواعيد المرتبطة بالمريض بترتيب زمني
$query = "SELECT A.id, A.date, A.time, D.firstName AS doctor_name, D.uniqueFileName AS doctor_photo, A.status 
          FROM Appointment A
          JOIN Doctor D ON A.DoctorID = D.id
          WHERE A.PatientID = ? 
          ORDER BY A.date, A.time";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Homepage</title>
    <link rel="stylesheet" href="../css/HFstyle.css">
    <link rel="stylesheet" href="../css/Patient.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.html"><img src="../images/logo.png" alt="Rifq Logo"> <span id="rifq">Rifq</span><span id="clinic">Clinic</span></a>
            </div>
            <div class="header-button">
                <a href="logout.php"><img src="../images/LogOut.PNG" alt="Log out"></a>
            </div>
        </div>
    </header>

    <div class="page-banner-area">
        <div class="page-banner-image">
            <img src="../images/Patientinfo.png" alt="Banner Image">
        </div>
        <div class="page-banner-content">
            <div class="content-text">
            <h1>Welcome <?php echo htmlspecialchars($patient['firstName']); ?>!</h1>
            <p><strong>Name:</strong> <?php echo htmlspecialchars(($patient['firstName'] ?? 'Unknown') . ' ' . ($patient['lastName'] ?? '')); ?></p>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($patient['id'] ?? 'N/A'); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['Gender'] ?? 'Not specified'); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo isset($patient['DoB']) ? date('j/n/Y', strtotime($patient['DoB'])) : 'Unknown'; ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['emailAddress'] ?? 'No email available'); ?></p>
            </div>
        </div>
    </div>

    <div class="appointments-container">
        <h2 class="section-title">My Appointments</h2>
        <div class="appointment-actions">
            <a href="book_appointment.php" class="book-appointment">Book an appointment</a>
        </div>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Doctor’s Name</th>
                    <th>Doctor’s Photo</th>
                    <th>Status</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                    <td><img src="../images/<?php echo htmlspecialchars($row['doctor_photo']); ?>" alt="Doctor's Photo"></td>
                    <td><span class="status <?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                    <td><a href="cancel_appointment.php?id=<?php echo $row['id']; ?>" class="cancel-link">Cancel</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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



