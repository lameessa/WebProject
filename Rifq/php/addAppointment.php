<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<?php
session_start();
include 'AuthCheck.php';

if ($_SESSION['user_type'] !== 'patient') {
    header("Location: index.php");
    exit();
}

$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $required_fields = ['doctor_id', 'patient_id', 'date', 'time', 'reason'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            header("Location: Appointment.php?error=missing_fields");
            exit();
        }
    }

    $doctor_id = (int)$_POST['doctor_id'];
    $patient_id = (int)$_POST['patient_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = "Pending";

    if (!strtotime($date) || !strtotime($time)) {
        header("Location: Appointment.php?error=invalid_date_time");
        exit();
    }

    $stmt = $connection->prepare("INSERT INTO Appointment (DoctorID, PatientID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $doctor_id, $patient_id, $date, $time, $reason, $status);

    if ($stmt->execute()) {
        header("Location: Patient.php?msg=Appointment+booked+successfully");
        exit();
    } else {
        header("Location: Appointment.php?error=database_error&message=" . urlencode($stmt->error));
        exit();
    }
} else {
    header("Location: Appointment.php");
    exit();
}
?>

