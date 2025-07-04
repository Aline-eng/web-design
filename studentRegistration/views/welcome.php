<?php
// Receive the student's name from URL after registration
$name = isset($_GET['name']) ? $_GET['name'] : "Student";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="welcome-box">
        <h2>🎉 Welcome, <?= htmlspecialchars($name); ?>!</h2>
        <p>Your registration was successful.</p>
</br>
        <a href="view_students.php">View All Registered Students</a>
</br></br></br></br></br>
        <a href="student_registration.html">Add Student</a>
    </div>

</body>
</html>
