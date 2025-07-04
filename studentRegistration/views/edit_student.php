<?php
include '../db/db.php';

if (!isset($_GET['id'])) {
    die("No student selected.");
}

$id = $_GET['id'];

// Get current student data
$query = $conn->prepare("SELECT * FROM student_tb WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Edit Student: <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h2>

    <form action="../actions/update_student.php" method="POST" style="display: flex; justify-content: center;">
        <input type="hidden" name="id" value="<?= $student['id'] ?>">
        <table>
            <tr>
                <td>First Name:</td>
                <td><input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required></td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td><input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required></td>
            </tr>
            <tr>
                <td>Mobile:</td>
                <td><input type="tel" name="mobile" value="<?= $student['mobile'] ?>"></td>
            </tr>
            <tr>
                <td>Course Applied:</td>
                <td><input type="text" name="courses_applied" value="<?= $student['courses_applied'] ?>"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <input type="submit" value="Update">
                    <a href="view_students.php" style="margin-left: 10px; color: darkblue;">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
