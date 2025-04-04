<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


session_start();

$servername = "localhost";
$username   = "root";
$password   = "root";
$database   = "Rifq";

// الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من الطلب
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $pass     = $_POST['password'];
    $userType = $_POST['userType']; // "doctor" or "patient"

    // نحدد الجدول حسب الدور
    if ($userType === "doctor") {
        $sql = "SELECT id, firstName, password FROM Doctor WHERE emailAddress = ?";
    } else {
        $sql = "SELECT id, firstName, password FROM Patient WHERE emailAddress = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // وجدنا مستخدم بهذا الإيميل
        $row = $result->fetch_assoc();
        // التحقق من كلمة المرور
        if (password_verify($pass, $row['password'])) {
            // تسجيل جلسة
            $_SESSION['userID']   = $row['id'];
            $_SESSION['userType'] = $userType;
            $_SESSION['firstName']= $row['firstName'];

            // توجيه المستخدم
            if ($userType === "doctor") {
                header("Location: ../html/Doctor.html");
            } else {
                header("Location: ../html/Patient.html");
            }
            exit();
        } else {
            // كلمة مرور خاطئة
            header("Location: Login.html?error=" . urlencode("Invalid password!"));
            exit();
        }
    } else {
        // لا يوجد مستخدم بهذا الإيميل
        header("Location: Login.html?error=" . urlencode("Email not found!"));
        exit();
    }

} else {
    // إذا حاولوا دخول الصفحة مباشرة
    header("Location: Login.php");
    exit();
}

$conn->close();
?>