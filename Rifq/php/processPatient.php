<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
$conn = new mysqli("localhost", "root", "root", "Rifq");
if ($conn->connect_error) die("Connection failed");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'],
              $_POST['nationalID'], $_POST['gender'], $_POST['dob'])) {
        header("Location: Signup.php?error=Missing patient data!"); exit();
    }
    $firstName = $_POST['firstName']; $lastName = $_POST['lastName'];
    $email = $_POST['email']; $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender']; $dob = $_POST['dob']; $nationalID = $_POST['nationalID'];
    $sql = "INSERT INTO Patient (id, firstName, lastName, emailAddress, password, Gender, DoB)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $nationalID, $firstName, $lastName, $email, $password, $gender, $dob);
    if ($stmt->execute()) {
        $_SESSION['user_type'] = 'patient'; $_SESSION['user_id'] = $nationalID; $_SESSION['email'] = $email;
        header("Location: Patient.php"); exit();
    } else {
        header("Location: Signup.php?error=" . urlencode("DB Error: " . $stmt->error)); exit();
    }
}
$conn->close();
?>