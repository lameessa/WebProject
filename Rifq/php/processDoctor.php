<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
$conn = new mysqli("localhost", "root", "root", "Rifq");
if ($conn->connect_error) die("Connection failed");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'],
              $_POST['nationalID'], $_POST['specialityID'], $_FILES['profilePic'])) {
        header("Location: Signup.php?error=Missing data!"); exit();
    }
    $firstName = $_POST['firstName']; $lastName = $_POST['lastName'];
    $email = $_POST['email']; $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nationalID = $_POST['nationalID']; $specialityID = $_POST['specialityID'];
    $uniqueName = uniqid('', true) . '.' . pathinfo($_FILES["profilePic"]["name"], PATHINFO_EXTENSION);
    $profilePicPath = "uploads/" . $uniqueName;
    if (!move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePicPath)) {
        header("Location: Signup.php?error=Upload error!"); exit();
    }
    $sql = "INSERT INTO Doctor (id, firstName, lastName, emailAddress, password, SpecialityID, uniqueFileName)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssis", $nationalID, $firstName, $lastName, $email, $password, $specialityID, $uniqueName);
    if ($stmt->execute()) {
        $_SESSION['user_type'] = 'doctor'; $_SESSION['user_id'] = $nationalID; $_SESSION['email'] = $email;
        header("Location: Doctor.php"); exit();
    } else {
        header("Location: Signup.php?error=" . urlencode("DB Error: " . $stmt->error)); exit();
    }
}
$conn->close();
?>