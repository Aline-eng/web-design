<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">

</head>
<body class="welcome">
    <div class="welcome-box">
        <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?>! 🎉</h1>
        <p>You have successfully logged in as <strong><?php echo htmlspecialchars($user['role']); ?></strong>.</p>

        <p>Here are some things you can do from here:</p>
        <ul>
            <li>✔️ Explore your profile information</li>
            <li>✔️ Update your settings (coming soon)</li>
            <li>✔️ Access user/admin features</li>
            <li>✔️ Contact support if you need help</li>
        </ul></br></br>

        <a class="logout-btn" href="logout.php">Logout</a>
    </div>
</body>

