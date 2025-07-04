<?php
$conn = new mysqli("localhost", "root", "", "login_signup_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>