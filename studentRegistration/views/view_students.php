<?php
include '../db/db.php';

// Handle Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pagination Setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total records for pagination
$countQuery = "SELECT COUNT(*) AS total FROM student_tb WHERE first_name LIKE '%$search%' OR email LIKE '%$search%'";
$totalResult = $conn->query($countQuery)->fetch_assoc();
$totalRecords = $totalResult['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch filtered and paginated results
$sql = "SELECT * FROM student_tb 
        WHERE first_name LIKE '%$search%' OR email LIKE '%$search%' 
        ORDER BY id DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registered Students</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Registered Students</h2>

<form class="search-form" method="GET">
    <input type="text" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>">
    <input type="submit" value="Search">
</form>

<table class="students">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>DOB</th>
        <th>Course</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['dob'] ?></td>
            <td><?= $row['courses_applied'] ?></td>
            <td class="actions">
                <a href="edit_student.php?id=<?= $row['id'] ?>">Edit</a>
                <a href="../actions/delete_student.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure to delete this student?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Pagination -->
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<div class="welcome-box">
    <a href="welcome.php">Return to welcome page</a>
</div>
</body>
</html>
