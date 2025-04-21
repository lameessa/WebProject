<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


session_start();
include 'AuthCheck.php';

if ($_SESSION['user_type'] !== 'patient') {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['error' => 'Access denied']);
    exit();
}

$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["specialty_id"])) {
    $selectedSpecialtyId = (int)$_POST["specialty_id"];
    
    $query = "SELECT id, firstName, lastName FROM Doctor WHERE SpecialityID = ?";
    $stmt = $connection->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['error' => 'Prepare failed: ' . $connection->error]);
        exit();
    }
    
    $stmt->bind_param("i", $selectedSpecialtyId);
    
    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Execute failed: ' . $stmt->error]);
        exit();
    }
    
    $result = $stmt->get_result();
    $doctors = [];
    
    while ($row = $result->fetch_assoc()) {
        $doctor = [
            'id' => $row['id'],
            'firstName' => $row['firstName'],
            'lastName' => $row['lastName']
        ];
        $doctors[] = $doctor;
    }
    
    if (empty($doctors)) {
        echo json_encode(['message' => 'No doctors found for this specialty']);
    } else {
        echo json_encode($doctors);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$stmt->close();
$connection->close();
?>