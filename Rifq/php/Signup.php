<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Sign Up</title>
  <link rel="stylesheet" href="../css/signstyle.css" />
    <link rel="stylesheet" href="../css/HFstyle.css">
</head>
<body>
<header>
  <div class="mcontainer">
    <div class="logo">
      <a href="../php/index.php">
        <img src="../images/logo.png" alt="Rifq Logo" />
        <span id="rifq">Rifq</span><span id="clinic">Clinic</span>
      </a>
    </div>
  </div>
</header>
<div class="container">
  <h2>Sign Up</h2>
  <div id="errorMsg" style="color:red; font-weight:bold; margin-bottom:10px;"></div>
  <form id="signup-form" action="" method="POST" enctype="multipart/form-data">
    <div class="radio-container">
      <label>Select Role:</label>
      <input type="radio" id="patient" name="userType" value="patient" required />
      <label for="patient">Patient</label>
      <input type="radio" id="doctor" name="userType" value="doctor" required />
      <label for="doctor">Doctor</label>
    </div>
    <input type="text" name="firstName" placeholder="First Name" required /><br />
    <input type="text" name="lastName" placeholder="Last Name" required /><br />
    <input type="number" name="nationalID" placeholder="National ID" required /><br />
    <input type="email" name="email" placeholder="Email" required /><br />
    <input type="password" name="password" placeholder="Password" required /><br />
    <div id="patient-fields" style="display:none;">
      <label>Gender:</label>
      <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select><br />
      <label>Date of Birth:</label>
      <input type="date" name="dob" /><br />
    </div>
    <div id="doctor-fields" style="display:none;">
      <label>Speciality:</label>
      <select name="specialityID">
        <option value="1">General Medicine</option>
        <option value="2">Pediatrics</option>
        <option value="3">Cardiology</option>
        <option value="4">Neurology</option>
      </select><br />
      <label>Upload Photo:</label>
      <input type="file" name="profilePic" accept="image/*" /><br />
    </div>
    <button type="submit" class="btn">Register</button>
  </form>
</div>
<script>
  const radios = document.querySelectorAll('input[name="userType"]');
  radios.forEach((radio) => {
    radio.addEventListener("change", function () {
      let patientFields = document.getElementById("patient-fields");
      let doctorFields = document.getElementById("doctor-fields");
      if (this.value === "doctor") {
        doctorFields.style.display = "block";
        patientFields.style.display = "none";
      } else {
        doctorFields.style.display = "none";
        patientFields.style.display = "block";
      }
    });
  });
  const form = document.getElementById("signup-form");
  form.addEventListener("submit", function (e) {
    const role = document.querySelector('input[name="userType"]:checked');
    if (!role) {
      e.preventDefault();
      alert("Please select a role.");
      return;
    }
    if (role.value === "doctor") {
      form.action = "processDoctor.php";
    } else {
      form.action = "processPatient.php";
    }
  });
  const params = new URLSearchParams(window.location.search);
  if (params.has("error")) {
    const errorText = decodeURIComponent(params.get("error"));
    document.getElementById("errorMsg").textContent = errorText;
    history.replaceState({}, "", window.location.pathname);
  }
</script>
</body>
</html>