<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in'])) {
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
$stmt = $conn->prepare("SELECT fullname, email, username, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Community Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="images/logo.jpg" alt="Community Library Logo" class="logo">
        <h1>My Profile</h1>
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
        <div class="card">
            <div class="profile-header">
                <img src="images/profile-icon.png" alt="Profile Icon" class="profile-icon">
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
                    <p>Member since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>

            <div class="profile-details">
                <h3>Account Information</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Account Status:</strong> Active</p>
            </div>

            <div class="profile-actions">
                <h3>Account Management</h3>
                <a href="edit_profile.php?id=<?= $_SESSION['user_id'] ?>" class="btn">Edit Profile</a>
                <a href="delete.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete your profile! This action cannot be undone?');">Delete Account</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Community Library. All rights reserved.</p>
    </footer>

</body>
</html>
<?php
$conn->close();
?>