<?php
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login_page.php");
    exit();
}

// Database configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'library_system';

// Connect to database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate input
if (empty($_POST['username']) || empty($_POST['password'])) {
    $_SESSION['error'] = "Please enter both username and password.";
    header("Location: login_page.php");
    exit();
}

// Get form data
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
if (!$stmt) {
    $_SESSION['error'] = "An error occurred. Please try again later.";
    header("Location: login_page.php");
    exit();
}

$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    $_SESSION['error'] = "An error occurred. Please try again later.";
    header("Location: login_page.php");
    exit();
}

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: login_page.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: login_page.php");
    exit();
}
// Close database connections
if ($stmt) {
    $stmt->close();
}
$conn->close();
?>