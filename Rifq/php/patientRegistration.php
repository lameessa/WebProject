<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username   = "root";
$password   = "root";
$database   = "Rifq";

$connection = new mysqli($servername, $username, $password, $database);
if ($connection->connect_error) {
    header("Location: Signup.php?error=" . urlencode("❌ Connection failed: " . $connection->connect_error));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // التأكد من تعبئة الحقول المطلوبة للبيشنت
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || 
        empty($_POST['email'])     || empty($_POST['password']) ||
        empty($_POST['nationalID']) || empty($_POST['gender']) || empty($_POST['dob'])) {
        header("Location: Signup.php?error=" . urlencode("Some data is missing!"));
        exit();
    }

    $firstName    = $_POST['firstName'];
    $lastName     = $_POST['lastName'];
    $email        = $_POST['email'];
    $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nationalID   = $_POST['nationalID'];
    $gender       = $_POST['gender'];
    $dob          = $_POST['dob'];

    // 1) التحقق من تكرار الـ ID في جدول Doctor أو Patient
    $checkIDSql = "SELECT id FROM Doctor WHERE id = ?
                   UNION
                   SELECT id FROM Patient WHERE id = ?";
    $stmtID = $connection->prepare($checkIDSql);
    if (!$stmtID) {
        header("Location: Signup.php?error=" . urlencode("SQL Error: " . $connection->error));
        exit();
    }
    $stmtID->bind_param("ss", $nationalID, $nationalID);
    $stmtID->execute();
    $resID = $stmtID->get_result();
    if ($resID->num_rows > 0) {
        // يعني هناك سجل بنفس الـ ID
        header("Location: Signup.php?error=" . urlencode("National ID is already registered!"));
        exit();
    }
    $stmtID->close();

    // 2) التحقق من تكرار الإيميل في جدول الدكتور والبيشنت
    $checkSql = "SELECT 'doctor' as userType FROM Doctor WHERE emailAddress = ?
                 UNION
                 SELECT 'patient' as userType FROM Patient WHERE emailAddress = ?";
    $stmt = $connection->prepare($checkSql);
    if (!$stmt) {
        header("Location: Signup.php?error=" . urlencode("SQL Error: " . $connection->error));
        exit();
    }
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: Signup.php?error=" . urlencode("Email is already registered!"));
        exit();
    }
    $stmt->close();

    // 3) إدخال بيانات البيشنت في قاعدة البيانات
    $sql = "INSERT INTO Patient (id, firstName, lastName, emailAddress, password, Gender, DoB)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $connection->prepare($sql);
    if (!$stmtInsert) {
        header("Location: Signup.php?error=" . urlencode("SQL Error: " . $connection->error));
        exit();
    }
    $stmtInsert->bind_param("issssss", $nationalID, $firstName, $lastName, $email, $passwordHash, $gender, $dob);

    if ($stmtInsert->execute()) {
        $_SESSION['user_type'] = "patient";
        $_SESSION['user_id']   = $nationalID;
        $_SESSION['email']     = $email;
        header("Location: Patient.php");
        exit();
    } else {
       
        header("Location: Signup.php?error=" . urlencode("Error inserting data: " . $stmtInsert->error));
        exit();
    }
    $stmtInsert->close();
}

$connection->close();
?>
