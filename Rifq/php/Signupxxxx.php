<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$servername = "localhost";
$username   = "root";
$password   = "root";
$database   = "Rifq";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// التحقق من الطلب
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['firstName']) || empty($_POST['lastName']) 
     || empty($_POST['email']) || empty($_POST['password'])) {
        // نعيد التوجيه للصفحة مع بارامتر الخطأ
        header("Location: Signup.html?error=" . urlencode("Some data is missing!"));
        exit();
    }

    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $email     = $_POST['email'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $userType  = $_POST['userType']; 
    $nationalID= $_POST['nationalID']; 

   
    // نبحث في الجدولين Doctor وPatient
$checkSql = "SELECT 'doctor' as userType FROM Doctor WHERE emailAddress = ?
UNION
SELECT 'patient' as userType FROM Patient WHERE emailAddress = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("ss", $email, $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
        // الإيميل موجود
        header("Location: Signup.html?error=" . urlencode("Email is already registered!"));
        exit();
    }

    // **2) إذا مش موجود، نُكمل التسجيل**
    if ($userType == "doctor") {
        $specialityID = $_POST['specialityID'];
        $profilePic   = NULL;

        // رفع صورة الطبيب
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['size'] > 0) {
            $targetDir = "uploads/";
            $profilePicName = basename($_FILES["profilePic"]["name"]);
            $profilePicPath = $targetDir . $profilePicName;

            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePicPath)) {
                $profilePic = $profilePicName; 
            } else {
                header("Location: Signup.html?error=" . urlencode("Error uploading the picture!"));
                exit();
            }
        }

        // إدخال بيانات الطبيب
        $sql = "INSERT INTO Doctor (firstName, lastName, emailAddress, password, SpecialityID, uniqueFileName) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $firstName, $lastName, $email, $password, $specialityID, $profilePic);

    } elseif ($userType == "patient") {
        $gender = $_POST['gender'];
        $dob    = $_POST['dob'];

        $sql = "INSERT INTO Patient (firstName, lastName, emailAddress, password, Gender, DoB) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $gender, $dob);
    } else {
        header("Location: Signup.html?error=" . urlencode("Unknown role!"));
        exit();
    }

    // تنفيذ الاستعلام والتحقق
    if ($stmt->execute()) {
        $_SESSION['userType'] = $userType;
        $_SESSION['email']    = $email;

        // توجيه المستخدم
        if ($userType == "doctor") {
            header("Location: ../html/Doctor.html");
        } else {
            header("Location: ../html/Patient.html");
        }
        exit();
    } else {
        header("Location: Signup.html?error=" . urlencode("Error inserting data: " . $stmt->error));
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
