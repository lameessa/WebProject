<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();


include 'AuthCheck.php';


if ( $_SESSION['user_type'] !== 'patient') {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['user_id']; 

$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_POST['patient_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = "Pending";

    // تحويل التاريخ إلى صيغة datetime كاملة
    $datetime = date("Y-m-d", strtotime($date));

    $stmt = $connection->prepare("INSERT INTO Appointment (DoctorID, PatientID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $doctor_id, $patient_id, $datetime, $time, $reason, $status);

    if ($stmt->execute()) {
        // بعد الحجز، إعادة توجيه المستخدم إلى صفحة المريض برسالة
        header("Location: Patient.php?msg=Appointment+booked+successfully");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    // منع الوصول المباشر بدون POST
    header("Location: Appointment.php");
    exit();
}
?>

