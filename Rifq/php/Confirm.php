<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

// GET APPOINTMENT ID
if(!isset($_GET['appointment_id'])) {
  header("Location: Doctor.php");
  exit();
}
$appointmentID = $_GET['appointment_id'];

// CONNECT
include 'Connection.php';
if(!$connection){
  die("Connection failed: ".mysqli_connect_error());
}
// SQL
$sql = "UPDATE Appointment SET status = 'Confirmed' WHERE id = '$appointmentID'";
mysqli_query($connection, $sql);
mysqli_close($connection);

// HOME
header("Location: Doctor.php");
exit();
