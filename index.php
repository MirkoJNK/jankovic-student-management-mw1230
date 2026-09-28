<?php
require 'db.php';

$message = $_GET['message'] ?? '';
$stmt = $pdo->query('SELECT * FROM students ORDER BY id DESC');
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <header class="page-header">
        <div>
            <p class="eyebrow">Mini System • CRUD</p>
            <h1>Student Management System</h1>
            <p class="subtitle">Create, view, update, and delete student records.</p>
        </div>
        <a class="btn btn-primary" href="create.php">+ Add Student</a>
    </header>

    <?php if ($message): ?>
        <div class="alert success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <section class="card">
        <div class="table-header">
            <h2>Student Records</h2>
            <span class="count"><?= count($students) ?> record<?= count($students) === 1 ? '' : 's' ?></span>
        </div>

        <?php if (count($students) === 0): ?>
            <div class="empty-state">
                <h3>No students yet</h3>
                <p>Add your first student record to get started.</p>
                <a class="btn btn-primary" href="create.php">Add Student</a>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= (int)$student['id'] ?></td>
                            <td><?= htmlspecialchars($student['student_id']) ?></td>
                            <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['course']) ?></td>
                            <td><?= (int)$student['year_level'] ?></td>
                            <td class="actions">
                                <a class="btn btn-small btn-secondary" href="edit.php?id=<?= (int)$student['id'] ?>">Edit</a>
                                <form action="delete.php" method="POST" onsubmit="return confirm('Delete this student record?');">
                                    <input type="hidden" name="id" value="<?= (int)$student['id'] ?>">
                                    <button class="btn btn-small btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
