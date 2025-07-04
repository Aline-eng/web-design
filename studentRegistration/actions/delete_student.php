<?php
include '../db/db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM student_tb WHERE id = $id");
}
header("Location: ../views/view_students.php");
?>
