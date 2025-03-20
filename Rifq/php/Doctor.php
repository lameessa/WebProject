<?php
// SESSION
session_start();

// LOGIN
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// DOCTOR
if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'doctor') {
    $doctor_id = $_SESSION['user_id'];



// CONNECT TO DATABASE
$connection = mysqli_connect("localhost","root","root","Rifq");
if(!$connection){
  die("Connection failed: ".mysqli_connect_error());
} else {
    // SQL QUERIES
    $sql1 = "SELECT id, firstName, lastName, uniqueFileName, SpecialityID, emailAddress FROM Doctor WHERE id='$doctor_id'";
    $res1 = mysqli_query($connection, $sql1);
    $docRow = mysqli_fetch_assoc($res1);
    $specID = $docRow['SpecialityID'];
    $sql2 = "SELECT speciality FROM Speciality WHERE id='$specID'";
    $res2 = mysqli_query($connection, $sql2);
    $specRow = mysqli_fetch_assoc($res2);
    $docSpec = $specRow['speciality'];
    $sql3 = "SELECT * FROM Appointment WHERE DoctorID='$doctor_id' AND status IN('Pending','Confirmed') ORDER BY date ASC, time ASC";
    $res3 = mysqli_query($connection, $sql3);
    $sql4 = "SELECT PatientID FROM Appointment WHERE DoctorID='$doctor_id' AND status='Done' ORDER BY PatientID ASC";
    $res4 = mysqli_query($connection, $sql4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Rifq | Doctor</title>
  <link rel="stylesheet" href="../css/docstyle.css">
  <link rel="stylesheet" href="../css/HFstyle.css">
</head>
<body>
  <header>
    <div class="mcontainer">
      <div class="logo">
        <a href="index.php">
          <img src="../images/logo.png" alt="Rifq Logo">
          <span id="rifq">Rifq</span><span id="clinic">Clinic</span>
        </a>
      </div>
      <div class="header-button">
        <a href="Logout.php"><img src="../images/LogOut.png" alt="Log out"></a>
      </div>
    </div>
  </header>
  <div class="container">
    <div class="doctor-info">
      <div class="left">
        <h2>Welcome Doctor <?php echo $docRow['firstName']; ?>!</h2>
        <p><strong>Name:</strong> Dr. <?php echo $docRow['firstName'] ." " .$docRow['lastName']; ?></p>
        <p><strong>ID:</strong> <?php echo $docRow['id']; ?></p>
        <p><strong>Specialty:</strong> <?php echo $docSpec; ?></p>
        <p><strong>Email:</strong> <?php echo $docRow['emailAddress']; ?></p>
      </div>
      <div class="right">
          <img src="../images/<?php echo $docRow['uniqueFileName']; ?>" alt="Doctor Image">
      </div>
    </div>
    <div class="appointments-table">
      <h3>Upcoming Appointments</h3>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Patient's Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Reason</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php
        // UPCOMING APPOINTMENTS TABLE
        if(mysqli_num_rows($res3) > 0){
          while($row = mysqli_fetch_assoc($res3)){
            $patientID = $row['PatientID'];
            $pQ = "SELECT firstName, lastName, Gender, DoB FROM Patient WHERE id='$patientID'";
            $pR = mysqli_query($connection, $pQ);
            $pRow = mysqli_fetch_assoc($pR);
            $age = 0;
            if($pRow){
              $dob = new DateTime($pRow['DoB']);
              $now = new DateTime();
              $age = $now->diff($dob)->y;
            }
            echo "<tr>";
            echo "<td>" .substr($row['date'],0,10) ."</td>";
            echo "<td>" .$row['time'] ."</td>";
            echo "<td>" .$pRow['firstName'] ." " .$pRow['lastName'] ."</td>";
            echo "<td>" .$age ."</td>";
            echo "<td>" .$pRow['Gender'] ."</td>";
            echo "<td>" .$row['reason'] ."</td>";
            echo "<td>";
                //STATUS
                if($row['status']==='Pending'){
                    echo '<span style="color:orange;"> ‎  ‎Pending</span><br><a class="btn" href="Confirm.php?appointment_id=' .$row['id'] .'">Confirm</a>';
                } elseif($row['status']==='Confirmed'){
                    echo '‎<span style="color:green;"> ‎ Confirmed</span><br><a class="btn" href="Medication.php?appointment_id=' .$row['id'] .'">Prescribe</a>';
                }
            echo "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7'>No upcoming appointments</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
    <div class="patients-table">
      <h3>Your Patients</h3>
      <table>
        <thead>
          <tr>
            <th>Patient's Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Medications</th>
          </tr>
        </thead>
        <tbody>
        <?php
        // PATIENTS TABLE
        if(mysqli_num_rows($res4) > 0){
          while($doneID = mysqli_fetch_assoc($res4)){
            $pID = $doneID['PatientID'];
            $sql5 = "SELECT firstName, lastName, Gender, DoB FROM Patient WHERE id='$pID' ORDER BY firstName ASC, lastName ASC";
            $res5 = mysqli_query($connection, $sql5);
            $doneRow = mysqli_fetch_assoc($res5);
            if($doneRow){
              $dob2 = new DateTime($doneRow['DoB']);
              $now2 = new DateTime();
              $age2 = $now2->diff($dob2)->y;
              // TO GET MEDICATIONS
              $appsSql = "SELECT id FROM Appointment WHERE DoctorID='$doctor_id' AND PatientID='$pID' AND status='Done'";
              $appsRes = mysqli_query($connection, $appsSql);
              $allMeds = array();
              while($appRow = mysqli_fetch_assoc($appsRes)){
                $appID = $appRow['id'];
                $presSql = "SELECT MedicationID FROM Prescription WHERE AppointmentID='$appID'";
                $presRes = mysqli_query($connection, $presSql);
                while($presRow = mysqli_fetch_assoc($presRes)){
                    $medID = $presRow['MedicationID'];
                    $medSql = "SELECT MedicationName FROM Medication WHERE id='$medID'";
                    $medRes = mysqli_query($connection, $medSql);
                    $medRow = mysqli_fetch_assoc($medRes);
                    if($medRow){
                        $allMeds[] = $medRow['MedicationName'];
                    }
                }
              }
          $uniqueMeds = array_unique($allMeds);
          $medString = implode(", ", $uniqueMeds);
              echo "<tr>";
              echo "<td>" .$doneRow['firstName'] ." " .$doneRow['lastName'] ."</td>";
              echo "<td>" .$age2 ."</td>";
              echo "<td>" .$doneRow['Gender'] ."</td>";
              echo "<td>" .$medString ."</td>";
              echo "</tr>";
            }
          }
        } else {
          echo "<tr><td colspan='4'>No patients found.</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>
  <footer id="footer" class="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="single-footer">
              <h2>About Us</h2>
              <p>At Rifq Clinic, we are dedicated to providing exceptional veterinary care for your beloved pets. Our team of experienced professionals is committed to ensuring the health and well-being of your furry companions through personalized treatment plans and compassionate service.</p>
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
            <p>© Copyright 2025 | All Rights Reserved 
              by <span>IT329</span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</body>
</html>
<?php
    } mysqli_close($connection);
}
