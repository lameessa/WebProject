<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


// عرض جميع الأخطاء للتصحيح (اختياري أثناء التطوير)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// بيانات الاتصال بقاعدة البيانات
$servername = "localhost";
$username   = "root";
$password   = "root";
$database   = "Rifq";

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// إذا أُرسلت البيانات بطريقة POST، نعالج عملية التسجيل
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // تحقق من الحقول الأساسية
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || 
        empty($_POST['email'])     || empty($_POST['password'])) 
    {
        // إعادة التوجيه مع رسالة خطأ
        header("Location: Signup.php?error=" . urlencode("Some data is missing!"));
        exit();
    }

    // قراءة بيانات النموذج
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);  // تشفير كلمة المرور
    $userType  = $_POST['userType'];
    $nationalID= $_POST['nationalID'] ?? '';  // ربما تحتاجه لاحقًا
    // التحقق من وجود إيميل مسجل مسبقًا (في جدول الطبيب أو المريض)
    $checkSql = "SELECT 'doctor' as userType FROM Doctor WHERE emailAddress=?
                 UNION
                 SELECT 'patient' as userType FROM Patient WHERE emailAddress=?";

    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: Signup.php?error=" . urlencode("Email is already registered!"));
        exit();
    }

    // إذا كان المستخدم "doctor"
    if ($userType === "doctor") {
        $specialityID = $_POST['specialityID'] ?? 1;
        $profilePic   = NULL;

        // فحص رفع الصورة
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['size'] > 0) {
            $targetDir     = "uploads/";
            $originalName  = basename($_FILES["profilePic"]["name"]);
            $extension     = pathinfo($originalName, PATHINFO_EXTENSION);
            $uniqueName    = uniqid('', true) . '.' . $extension;
            $profilePicPath= $targetDir . $uniqueName;

            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePicPath)) {
                $profilePic = $uniqueName;  // سنحفظ فقط الاسم الفريد في قاعدة البيانات
            } else {
                header("Location: Signup.php?error=" . urlencode("Error uploading the picture!"));
                exit();
            }
        }

        // إدخال بيانات الطبيب
        $sql = "INSERT INTO Doctor (firstName, lastName, emailAddress, password, SpecialityID, uniqueFileName)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $firstName, $lastName, $email, $password, $specialityID, $profilePic);

    // إذا كان المستخدم "patient"
    } elseif ($userType === "patient") {
        $gender = $_POST['gender'] ?? 'Male';
        $dob    = $_POST['dob'] ?? NULL;

        $sql = "INSERT INTO Patient (firstName, lastName, emailAddress, password, Gender, DoB)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $gender, $dob);

    } else {
        header("Location: Signup.php?error=" . urlencode("Unknown role!"));
        exit();
    }

    // تنفيذ الاستعلام
    if ($stmt->execute()) {
        // تسجيل بيانات بالجلسة (اختياري عند الحاجة)
        $_SESSION['userType'] = $userType;
        $_SESSION['email']    = $email;

        // التوجيه بعد نجاح التسجيل
        if ($userType == "doctor") {
            header("Location: ../php/Doctor.php");
        } else {
            header("Location: ../php/Patient.php");
        }
        exit();
    } else {
        header("Location: Signup.php?error=" . urlencode("Error inserting data: " . $stmt->error));
        exit();
    }
    // إغلاق الاستعلام
    $stmt->close();
}

// إغلاق اتصال قاعدة البيانات
$conn->close();

// في حال لم يكن الطلب POST أو بعد إتمام المعالجة/عرض الأخطاء، نعرض صفحة التسجيل بالـHTML بالأسفل
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
      <!-- عدّل هذا الرابط حسب مكان الملف الرئيسي لديك -->
      <a href="../index.html">
        <img src="../images/logo.png" alt="Rifq Logo">
        <span id="rifq">Rifq</span><span id="clinic">Clinic</span>
      </a>
    </div>
  </div>
</header>

<div class="container">
  <h2>Sign Up</h2>

  <!-- مكان لعرض رسالة الخطأ إن وجدت -->
  <div id="errorMsg" style="color:red; font-weight:bold; margin-bottom:10px;"></div>

  <!-- نوجّه النموذج لنفس الصفحة -->
  <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" 
        method="POST" enctype="multipart/form-data">
    <!-- اختيار الدور -->
    <div class="radio-container">
      <label>Select Role:</label>
      <input type="radio" id="patient" name="userType" value="patient" required>
      <label for="patient">Patient</label>
      <input type="radio" id="doctor" name="userType" value="doctor" required>
      <label for="doctor">Doctor</label>
    </div>

    <!-- الحقول المشتركة بين المرضى والأطباء -->
    <input type="text"     name="firstName"  placeholder="First Name" required><br>
    <input type="text"     name="lastName"   placeholder="Last Name"  required><br>
    <input type="number"   name="nationalID" placeholder="National ID" required><br>
    <input type="email"    name="email"      placeholder="Email"      required><br>
    <input type="password" name="password"   placeholder="Password"   required><br>

    <!-- الحقول الخاصة بالمريض: نبدأها مخفية -->
    <div id="patient-fields" style="display:none;">
      <label>Gender:</label>
      <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select><br>

      <label>Date of Birth:</label>
      <input type="date" name="dob"><br>
    </div>

    <!-- الحقول الخاصة بالطبيب: نبدأها مخفية -->
    <div id="doctor-fields" style="display:none;">
      <label>Speciality:</label>
      <select name="specialityID">
        <option value="1">General Medicine</option>
        <option value="2">Pediatrics</option>
        <option value="3">Cardiology</option>
        <option value="4">Neurology</option>
      </select><br>

      <label>Upload Photo:</label>
      <input type="file" name="profilePic" accept="image/*">
    </div>

    <button type="submit" class="btn">Register</button>
  </form>
</div>

<script>
  // إظهار/إخفاء حقول الطبيب والمريض بناءً على اختيار الراديو
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

  // قراءة ?error=... من الرابط وعرضه للمستخدم
  const params = new URLSearchParams(window.location.search);
  if (params.has('error')) {
    const errorText = decodeURIComponent(params.get('error'));
    document.getElementById('errorMsg').textContent = errorText;
    // إزالة البارامتر بعد العرض كي لا يظهر عند إعادة التحديث
    history.replaceState({}, "", window.location.pathname);
  }
</script>

</body>
</html>
