<?php
session_start();

$connection = mysqli_connect("localhost", "root", "root", "Rifq");
if (!$connection) {
    echo "false";
    exit();
}

if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo "false";
    exit();
}

$appointment_id = intval($_POST['id']);

$query = "DELETE FROM Appointment WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $appointment_id);

if ($stmt->execute()) {
    echo "true";
} else {
    echo "false";
}

$stmt->close();
$connection->close();
?>
