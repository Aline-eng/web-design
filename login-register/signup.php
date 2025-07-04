<?php
include 'db.php';

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $fullname, $email, $password, $role);
$stmt->execute();

echo "Signup successful! <a href='login.html'>Click here to login</a>";
?>
