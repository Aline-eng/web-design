<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
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

// Get user data
$stmt = $conn->prepare("SELECT fullname, email, username FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validate inputs
    if (empty($fullname)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    // Check if email already exists for other users
    if ($email !== $user['email']) {
        $check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_email->bind_param("si", $email, $_SESSION['user_id']);
        $check_email->execute();
        if ($check_email->get_result()->num_rows > 0) {
            $errors[] = "Email already exists";
        }
    }
    
    // Handle password change if requested
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $errors[] = "Current password is required to set new password";
        }
        if (strlen($new_password) < 8) {
            $errors[] = "New password must be at least 8 characters long";
        }
        if ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match";
        }
        
        // Verify current password
        $verify_pwd = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $verify_pwd->bind_param("i", $_SESSION['user_id']);
        $verify_pwd->execute();
        $pwd_result = $verify_pwd->get_result()->fetch_assoc();
        
        if (!password_verify($current_password, $pwd_result['password'])) {
            $errors[] = "Current password is incorrect";
        }
    }
    
    // Update profile if no errors
    if (empty($errors)) {
        if (!empty($new_password)) {
            // Update with new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, password = ? WHERE id = ?");
            $update_stmt->bind_param("sssi", $fullname, $email, $hashed_password, $_SESSION['user_id']);
        } else {
            // Update without password change
            $update_stmt = $conn->prepare("UPDATE users SET fullname = ?, email = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $fullname, $email, $_SESSION['user_id']);
        }
        
        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Profile updated successfully";
            header("Location: profile.php");
            exit();
        } else {
            $errors[] = "Error updating profile: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Community Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="images/logo.jpg" alt="Community Library Logo" class="logo">
        <h1>Edit Profile</h1>
        <button class="logout-btn" onclick="location.href='logout.php'">
            <img src="images/logout-icon.webp" alt="Logout" class="logout-icon">
            <p style="font-size: 20px; color: white;">Logout</p>
        </button>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="profile.php">My Profile</a></li>
            <li><a href="#">Books</a></li>
            <li><a href="#">My Loans</a></li>
        </ul>
    </nav>

    <main>
        <div class="form-container card">
            <h2 class="form-title">Edit Your Profile</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit_profile.php" method="post">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    <small>Username cannot be changed</small>
                </div>

                <h3>Change Password (optional)</h3>
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                    <small>Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Community Library. All rights reserved.</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>