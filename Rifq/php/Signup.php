<?php
/*echo "<h2>📢 Received POST Data:</h2><pre>";
print_r($_POST);
echo "</pre>";*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//echo "🚀 Script is running!<br>";

$servername = "localhost";
$username = "root";
$password = "root";
$database = "Rifq";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} 

// echo "✅ Connected successfully!<br>"; 

// **التحقق من الطلب**
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /*echo "<h3>🔍 بيانات الفورم المستلمة:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";*/

    // **التأكد أن الفورم يرسل البيانات**
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['password'])) {
        die("❌ The problem: Some data is missing. Please ensure all fields are filled!");
    }

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // **تشفير كلمة المرور**
    $userType = $_POST['userType']; // **Doctor أو Patient**
    $nationalID = $_POST['nationalID']; // **الرقم الوطني**

    if ($userType == "doctor") {
        $specialityID = $_POST['specialityID'];
        $profilePic = NULL;

        // **رفع صورة الطبيب**
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['size'] > 0) {
            $targetDir = "uploads/";
            $profilePicName = basename($_FILES["profilePic"]["name"]);
            $profilePicPath = $targetDir . $profilePicName;

            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $profilePicPath)) {
                $profilePic = $profilePicName; // **تخزين اسم الملف فقط**
            } else {
                die("❌ Error uploading the picture!");
            }
        }

        // **إدخال بيانات الطبيب**
        $sql = "INSERT INTO Doctor (firstName, lastName, emailAddress, password, SpecialityID, uniqueFileName) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $firstName, $lastName, $email, $password, $specialityID, $profilePic);

    } elseif ($userType == "patient") {
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];

        // **إدخال بيانات المريض**
        $sql = "INSERT INTO Patient (firstName, lastName, emailAddress, password, Gender, DoB) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $gender, $dob);
    } else {
        die("❌ Error: Unknown role!");
    }

    // **تنفيذ الاستعلام والتحقق**
    if ($stmt->execute()) {
        $_SESSION['userType'] = $userType;
        $_SESSION['email'] = $email;

       // echo "✅ Data inserted successfully! Redirecting...";
        
        // **إعادة توجيه المستخدم**
        if ($userType == "doctor") {
            header("refresh:2; url= ../html/Doctor.html");
        } else {
            header("refresh:2; url= ../html/Patient.html");
        }
        exit();
    } else {
        die("❌ Error inserting data:" . $stmt->error);
    }

    $stmt->close();
}

// **إغلاق الاتصال بقاعدة البيانات**
$conn->close();
?>
