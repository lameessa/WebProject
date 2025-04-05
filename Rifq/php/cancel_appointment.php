<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();

// الاتصال بقاعدة البيانات
$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
 

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Appointment ID is missing.");
}

$appointment_id = intval($_GET['id']); // تحويل إلى عدد صحيح لمنع SQL Injection

// حذف الموعد من قاعدة البيانات
$query = "DELETE FROM Appointment WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $appointment_id);
if ($stmt->execute()) {
    // إعادة توجيه المريض إلى الصفحة الرئيسية بعد الحذف
    header("Location: Patient.php?message=Appointment Canceled Successfully");
    exit();
} else {
    echo "Error deleting appointment: " . $connection->error;
}

// إغلاق الاتصال
$stmt->close();
$connection->close();
?>

