
<?php
session_start();
if ($_SESSION['user_type'] !== 'doctor') {
    echo "false";
    exit();
}

if (isset($_GET['appointment_id'])) {
    $conn = mysqli_connect("localhost", "root", "root", "Rifq");
    if (!$conn) {
        echo "false";
        exit();
    }

    $appointment_id = $_GET['appointment_id'];
    $update = "UPDATE Appointment SET status='Confirmed' WHERE id='$appointment_id'";
    echo mysqli_query($conn, $update) ? "true" : "false";

    mysqli_close($conn);
} else {
    echo "false";
}
?>


