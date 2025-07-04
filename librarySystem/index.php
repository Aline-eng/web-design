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
    <title>Home - Community Library</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="images/logo.jpg" alt="Community Library Logo" class="logo">
        <h1>Welcome to Community Library</h1>
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
        <section class="card">
            <center><h2>Hello, <?php echo htmlspecialchars($user['fullname']); ?>🎉🎊</h2></br></center>
            <img src="images/library-banner.jpeg" alt="Library interior" class="banner">
            <p>Welcome back to your library account. You have full access to our collection and services.</p>
        </section>

        <article class="card">
            <h3>Quick Access</h3>
            <div class="quick-links">
                <div class="quick-link">
                    <h4>Your Profile</h4>
                    <p>View and update your personal information</p>
                    <a href="profile.php" class="btn">Go to Profile</a>
                </div>
                <div class="quick-link">
                    <h4>Book Search</h4>
                    <p>Find books in our collection</p>
                    <a href="#" class="btn">Search Books</a>
                </div>
                <div class="quick-link">
                    <h4>Your Loans</h4>
                    <p>View your current loans and due dates</p>
                    <a href="#" class="btn">View Loans</a>
                </div>
            </div>
        </article>
    </main>

    <footer>
        <p>&copy; 2025 Community Library. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
$conn->close();
?>