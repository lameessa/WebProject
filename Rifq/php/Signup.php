<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$servername = "localhost";
$username   = "root";
$password   = "root";
$database   = "Rifq";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || 
        empty($_POST['email'])     || empty($_POST['password'])) {
        header("Location: Signup.php?error=" . urlencode("Some data is missing!"));
        exit();
    }

    $firstName  = $_POST['firstName'];
    $lastName   = $_POST['lastName'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userType   = $_POST['userType'];
    $nationalID = $_POST['nationalID'];

    $checkSql = "SELECT 'doctor' as userType FROM Doctor WHERE emailAddress = ?
                 UNION
                 SELECT 'patient' as userType FROM Patient WHERE emailAddress = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: Signup.php?error=" . urlencode("Email is already registered!"));
        exit();
    }

    if ($userType === "doctor") {
        $specialityID = $_POST['specialityID'];
        $profilePic   = NULL;

        if (!isset($_FILES['profilePic']) || $_FILES['profilePic']['error'] !== 0) {
            header("Location: Signup.php?error=" . urlencode("You must upload a profile picture!"));
            exit();
        }

        $targetDir      = "uploads/";
        $originalName   = basename($_FILES["profilePic"]["name"]);
        $extension      = pathinfo($originalName, PATHINFO_EXTENSION);
        $uniqueName     = uniqid('', true) . '.' . $extension;
        $profilePicPath = $targetDir . $uniqueName;

        if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePicPath)) {
            $profilePic = $uniqueName;
        } else {
            header("Location: Signup.php?error=" . urlencode("Error uploading the picture!"));
            exit();
        }

        $sql = "INSERT INTO Doctor (firstName, lastName, emailAddress, password, SpecialityID, uniqueFileName)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $firstName, $lastName, $email, $password, $specialityID, $profilePic);

    } elseif ($userType === "patient") {
        $gender = $_POST['gender'];
        $dob    = $_POST['dob'];

        $sql = "INSERT INTO Patient (firstName, lastName, emailAddress, password, Gender, DoB)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $gender, $dob);
    } else {
        header("Location: Signup.php?error=" . urlencode("Unknown role!"));
        exit();
    }

    if ($stmt->execute()) {
        $_SESSION['user_type'] = $userType;
        $_SESSION['user_id']   = $conn->insert_id;
        $_SESSION['email']     = $email;

        if ($userType === "doctor") {
            header("Location: Doctor.php");
        } else {
            header("Location: Patient.php");
        }
        exit();
    } else {
        header("Location: Signup.php?error=" . urlencode("Error inserting data: " . $stmt->error));
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../css/signstyle.css">
</head>
<body>

<header>
  <div class="mcontainer">
    <div class="logo">
      <a href="../html/index.html">
        <img src="../images/logo.png" alt="Rifq Logo">
        <span id="rifq">Rifq</span><span id="clinic">Clinic</span>
      </a>
    </div>
  </div>
</header>

<div class="container">
  <h2>Sign Up</h2>
  <div id="errorMsg" style="color:red; font-weight:bold; margin-bottom:10px;"></div>

  <form id="signup-form" action="Signup.php" method="POST" enctype="multipart/form-data">
    <div class="radio-container">
      <label>Select Role:</label>
      <input type="radio" id="patient" name="userType" value="patient" required>
      <label for="patient">Patient</label>
      <input type="radio" id="doctor" name="userType" value="doctor" required>
      <label for="doctor">Doctor</label>
    </div>

    <input type="text" name="firstName" placeholder="First Name" required><br>
    <input type="text" name="lastName" placeholder="Last Name" required><br>
    <input type="number" name="nationalID" placeholder="National ID" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>

    <div id="patient-fields" style="display:none;">
      <label>Gender:</label>
      <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select><br>

      <label>Date of Birth:</label>
      <input type="date" name="dob"><br>
    </div>

    <div id="doctor-fields" style="display:none;">
      <label>Speciality:</label>
      <select name="specialityID">
        <option value="1">General Medicine</option>
        <option value="2">Pediatrics</option>
        <option value="3">Cardiology</option>
        <option value="4">Neurology</option>
      </select><br>

      <label>Upload Photo:</label>
      <input type="file" name="profilePic" accept="image/*" required><br>
    </div>

    <button type="submit" class="btn">Register</button>
  </form>
</div>

<script>
  document.querySelectorAll('input[name="userType"]').forEach((radio) => {
    radio.addEventListener('change', function() {
      let patientFields = document.getElementById('patient-fields');
      let doctorFields  = document.getElementById('doctor-fields');

      if (this.value === 'doctor') {
        doctorFields.style.display = "block";
        patientFields.style.display = "none";
      } else {
        patientFields.style.display = "block";
        doctorFields.style.display = "none";
      }
    });
  });

  const params = new URLSearchParams(window.location.search);
  if (params.has('error')) {
    const errorText = decodeURIComponent(params.get('error'));
    document.getElementById('errorMsg').textContent = errorText;
    history.replaceState({}, "", window.location.pathname);
  }
</script>

</body>
</html>
