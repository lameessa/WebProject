<?php
session_start();
include 'AuthCheck.php';

if ($_SESSION['user_type'] !== 'patient') {
    header("Location: index.php");
    exit();
}

include 'Connection,php';
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$patient_id = $_SESSION['user_id'];

$query = "SELECT firstName, lastName, emailAddress, DoB, Gender, id FROM Patient WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
if (!$patient) {
    die("<h2>Error: Patient not found</h2>");
}

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
    <style>
        .appointment-actions {
            margin-bottom: 50px !important;
        }
        .cancel-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php"><img src="../images/logo.png" alt="Rifq Logo"> <span id="rifq">Rifq</span><span id="clinic">Clinic</span></a>
            </div>
            <div class="header-button">
                <a href="Logout.php"><img src="../images/LogOut.png" alt="Log out"></a>
            </div>
        </div>
    </header>

    <div class="page-banner-area">
        <div class="page-banner-image">
            <img src="../images/Patientinfo.png" alt="Banner Image">
        </div>
        <div class="page-banner-content">
            <div class="content-text">
                <h1>Welcome <?php echo $patient['firstName']; ?>!</h1>
                <p><strong>Name:</strong> <?php echo ($patient['firstName'] ?? 'Unknown') . ' ' . ($patient['lastName'] ?? ''); ?></p>
                <p><strong>ID:</strong> <?php echo $patient['id'] ?? 'N/A'; ?></p>
                <p><strong>Gender:</strong> <?php echo $patient['Gender'] ?? 'Not specified'; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo isset($patient['DoB']) ? date('j/n/Y', strtotime($patient['DoB'])) : 'Unknown'; ?></p>
                <p><strong>Email:</strong> <?php echo $patient['emailAddress'] ?? 'No email available'; ?></p>
            </div>
        </div>
    </div>

    <div class="appointments-container">
        <h2 class="section-title">My Appointments</h2>
        <div class="appointment-actions">
            <a href="Appointment.php" class="book-appointment">Book an appointment</a>
        </div>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor’s Name</th>
                    <th>Doctor’s Photo</th>
                    <th>Status</th>
                    <th>Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments->fetch_assoc()): ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td><?php echo date('Y-m-d', strtotime($row['date'])); ?></td>
                    <td><?php echo $row['time']; ?></td>
                    <td><?php echo $row['doctor_name']; ?></td>
                    <td><img src="uploads/<?php echo $row['doctor_photo']; ?>" alt="Doctor's Photo"></td>
                    <td><span class="status <?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                    <td>
                        <button class="cancel-btn" data-id="<?php echo $row['id']; ?>">Cancel</button>
                    </td>
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

    <!-- jQuery + AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function(){
        $('.cancel-btn').click(function(){
            var row = $(this).closest('tr');
            var appointmentId = $(this).data('id');

            if(confirm("Are you sure you want to cancel this appointment?")){
                $.ajax({
                    url: 'cancel_appointment.php',
                    type: 'POST',
                    data: { id: appointmentId },
                    success: function(response){
                        if(response.trim() == 'true'){
                            row.remove();
                        } else {
                            alert("Failed to cancel the appointment.");
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
