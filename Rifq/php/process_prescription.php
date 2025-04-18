<?php

session_start();
include 'AuthCheck.php';

// Database Connection
$conn = mysqli_connect("localhost", "root", "root", "Rifq"); 

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if ( $_SESSION['user_type'] !== 'doctor') {
    header("Location: ../index.php");
    exit();
}
// Ensure form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $appointment_id = $_POST['appointment_id'];
    $medications = $_POST['medications'] ?? [];  // Default to empty array if not set

    // Update appointment status to "Done"
    $update_query = "UPDATE appointment SET status='Done' WHERE id='$appointment_id'";
    if (!mysqli_query($conn, $update_query)) {
        die("Error updating appointment status: " . mysqli_error($conn));
    }

    // Insert selected medications into the prescription table
    foreach ($medications as $medication_id) {
        $insert_query = "INSERT INTO prescription (AppointmentID, MedicationID) VALUES ('$appointment_id', '$medication_id')";
        if (!mysqli_query($conn, $insert_query)) {
            die("Error inserting prescription: " . mysqli_error($conn));
        }
    }

    mysqli_close($conn);

header("Location: ../php/Doctor.php");
    exit();
}
?>
