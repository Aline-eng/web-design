<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Access denied. Please submit the form.");
}

include '../db/db.php'; 


$first = $_POST['first_name'] ?? ''; 
$last = $_POST['last_name'] ?? ''; 
$email = $_POST['email'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$gender = $_POST['gender'] ?? '';
$dob = $_POST['dob'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$pin = $_POST['pin_code'] ?? '';
$state = $_POST['state'] ?? '';
$country = $_POST['country'] ?? '';
$hobbies = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : ''; 
$qualification = isset($_POST['qualification']) ? implode(", ", $_POST['qualification']) : ''; 
$course = isset($_POST['courses_applied']) ? implode(", ", $_POST['courses_applied']) : '';


$check = $conn->prepare("SELECT * FROM student_tb WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();  
$result = $check->get_result();
if ($result->num_rows > 0) { 
    echo "<h2 style='color:red; text-align:center;'>Email already exists! <a href='../views/student_registration.html'>Try again</a></h2>";
} else {
    
    $sql = "INSERT INTO student_tb ( 
        first_name, last_name, email, mobile, gender, dob, address, city, pin_code, state, country, hobbies, qualification, courses_applied
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssss", 
        $first, $last, $email, $mobile, $gender, $dob, $address, $city, $pin, $state, $country, $hobbies, $qualification, $course
    );

    if ($stmt->execute()) {
        header("Location: ../views/welcome.php?name=" . urlencode($first . " " . $last));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
