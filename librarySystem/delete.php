<?php
session_start();

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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to perform this action.";
    header("Location: login_page.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    
    // Ensure users can only delete their own account
    if ($id != $_SESSION['user_id']) {
        $_SESSION['error'] = "You can only delete your own account.";
        header("Location: profile.php");
        exit();
    }
    
    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Clear all session data and destroy the session
            session_unset();
            session_destroy();
            
            // Start a new session for the message
            session_start();
            $_SESSION['message'] = "Your account has been deleted successfully.";
            header("Location: login_page.php");
            exit();
        } else {
            $_SESSION['error'] = "Error deleting user: " . $stmt->error;
            header("Location: profile.php");
            exit();
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing delete statement: " . $conn->error;
        header("Location: profile.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: profile.php");
    exit();
}
?>
