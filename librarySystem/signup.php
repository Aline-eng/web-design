<?php
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

// Get form data
$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

// Validate inputs
$errors = [];

if (empty($fullname)) {
    $errors[] = "Full name is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

if (strlen($username) < 4) {
    $errors[] = "Username must be at least 4 characters.";
}

if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
}

// Check if username or email already exists
if (empty($errors)) {
    $check_user = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check_user->bind_param("ss", $username, $email);
    $check_user->execute();
    $result = $check_user->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Username or email already exists.";
    }
}

// If no errors, insert into database
if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $email, $username, $hashed_password);
    
    if ($stmt->execute()) {
        // Get the new user's ID
        $user_id = $stmt->insert_id;
        
        // Start session and set session variables
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['logged_in'] = true;
        $_SESSION['message'] = "Welcome to Community Library! Your account has been created successfully.";
        
        // Redirect to index page
        header("Location: index.php");
        exit();
    } else {
        $errors[] = "Error: " . $stmt->error;
    }
}

// If there are errors, display them
if (!empty($errors)) {
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Sign Up Error</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <img src="images/logo.png" alt="Community Library Logo" class="logo">
            <h1>Sign Up Error</h1>
        </header>
        <main>
            <div class="card">
                <h2 class="form-title">Oops! There was a problem</h2>
                <div class="alert alert-error">
                    <ul>';
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo '        </ul>
                </div>
                <p><a href="signup.html" class="btn">Try again</a></p>
            </div>
        </main>
        <footer>
            <p>&copy; 2025 Community Library. All rights reserved.</p>
        </footer>
    </body>
    </html>';
}

$conn->close();
?>