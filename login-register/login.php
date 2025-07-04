<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['user'] = $result->fetch_assoc();
    header("Location: welcome.php");
} else {
    echo "Invalid credentials. <a href='login.html'>Try again</a>";
}
?>
