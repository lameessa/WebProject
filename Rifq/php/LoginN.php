<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


session_start();

// إذا كان الطلب POST، نفذ عملية التحقق وتسجيل الدخول
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // بيانات الاتصال بقاعدة البيانات
    $servername = "localhost";
    $username   = "root";
    $password   = "root";
    $database   = "Rifq";

    // الاتصال بقاعدة البيانات
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // قراءة بيانات النموذج
    $email    = $_POST['email'];
    $pass     = $_POST['password'];
    $userType = $_POST['userType']; // "doctor" أو "patient"

    // اختيار الجدول المناسب حسب الدور
    if ($userType === "doctor") {
        $sql = "SELECT id, firstName, password FROM Doctor WHERE emailAddress = ?";
    } else {
        $sql = "SELECT id, firstName, password FROM Patient WHERE emailAddress = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // التحقق من وجود المستخدم
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // التحقق من صحة كلمة المرور باستخدام التشفير
        if (password_verify($pass, $row['password'])) {
            // حفظ بيانات الجلسة
            $_SESSION['userID']    = $row['id'];
            $_SESSION['userType']  = $userType;
            $_SESSION['firstName'] = $row['firstName'];

            // توجيه المستخدم للصفحة المناسبة
            if ($userType === "doctor") {
                header("Location: ../php/Doctor.php");
            } else {
                header("Location: ../php/Patient.php");
            }
            exit();
        } else {
            // كلمة المرور خاطئة
            header("Location: Login.php?error=" . urlencode("Invalid password!"));
            exit();
        }
    } else {
        // لا يوجد مستخدم بهذا الإيميل
        header("Location: Login.php?error=" . urlencode("Email not found!"));
        exit();
    }

    $conn->close();
}

// في حال لم يكن POST أو بعد عرض الأخطاء، سيستمر العرض للجزء HTML أدناه
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
  
    <link rel="stylesheet" href="HFstyle.css">
    <link rel="stylesheet" href="LoginStyle.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <!-- عدّل الرابط حسب مكان الصفحة الرئيسية لديك -->
            <a href="../index.html">
                <img src="../images/logo.png" alt="Rifq Logo">
                <span id="rifq">Rifq</span><span id="clinic">Clinic</span>
            </a>
        </div>
    </div>
</header>

<div class="login-container">
    <div class="login-form">
        <h2>Login</h2>

        <!-- مكان لعرض الخطأ إن وجد -->
        <div id="errorMsg" style="color:red; font-weight:bold; margin-bottom:10px;"></div>

        <!-- نجعل action يشير لنفس الصفحة -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role">User Role</label>
            <select id="role" name="userType">
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
            </select>

            <button type="submit">Login</button>
        </form>
    </div>

    <div class="dog-login">
        <img src="../images/dog2.PNG" alt="">
    </div>
</div>

<footer id="footer" class="footer ">
    <!-- Footer Top -->
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
    <!--/ End Footer Top -->
    <!-- Copyright -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="copyright-content">
                    <p>© Copyright 2025  |  All Rights Reserved by <span>IT329</span> </p>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Copyright -->
</footer>

<script src="../js/Login.js"></script>

<script>
  // لو فيه ?error=... في الرابط، نعرض الرسالة للمستخدم
  const params = new URLSearchParams(window.location.search);
  if (params.has('error')) {
    const errorText = decodeURIComponent(params.get('error'));
    document.getElementById('errorMsg').textContent = errorText;
    // إزالة البارامتر من الرابط حتى لا يبقى بعد إعادة التحميل
    history.replaceState({}, "", window.location.pathname);
  }
</script>

</body>
</html>
