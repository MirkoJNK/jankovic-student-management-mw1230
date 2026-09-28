<?php
require 'db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: index.php?message=' . urlencode('Student record not found.'));
    exit;
}

$errors = [];
$data = $student;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['student_id'] = trim($_POST['student_id'] ?? '');
    $data['first_name'] = trim($_POST['first_name'] ?? '');
    $data['last_name'] = trim($_POST['last_name'] ?? '');
    $data['email'] = trim($_POST['email'] ?? '');
    $data['course'] = trim($_POST['course'] ?? '');
    $data['year_level'] = trim($_POST['year_level'] ?? '');

    if ($data['student_id'] === '') $errors[] = 'Student ID is required.';
    if ($data['first_name'] === '') $errors[] = 'First name is required.';
    if ($data['last_name'] === '') $errors[] = 'Last name is required.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
    if ($data['course'] === '') $errors[] = 'Course is required.';
    if (!in_array($data['year_level'], ['1', '2', '3', '4', '5'], true)) $errors[] = 'Year level must be between 1 and 5.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare(
                'UPDATE students
                 SET student_id = ?, first_name = ?, last_name = ?, email = ?, course = ?, year_level = ?
                 WHERE id = ?'
            );
            $stmt->execute([
                $data['student_id'],
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['course'],
                $data['year_level'],
                $id,
            ]);
            header('Location: index.php?message=' . urlencode('Student updated successfully.'));
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = 'Student ID or email already exists.';
            } else {
                $errors[] = 'Unable to update the student record.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container narrow">
    <a class="back-link" href="index.php">← Back to records</a>
    <section class="card form-card">
        <h1>Edit Student</h1>
        <p class="subtitle">Update the selected student record.</p>

        <?php if ($errors): ?>
            <div class="alert danger">
                <strong>Please fix the following:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <label>
                Student ID
                <input type="text" name="student_id" value="<?= htmlspecialchars($data['student_id']) ?>" required>
            </label>
            <label>
                First Name
                <input type="text" name="first_name" value="<?= htmlspecialchars($data['first_name']) ?>" required>
            </label>
            <label>
                Last Name
                <input type="text" name="last_name" value="<?= htmlspecialchars($data['last_name']) ?>" required>
            </label>
            <label>
                Email
                <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
            </label>
            <label>
                Course
                <input type="text" name="course" value="<?= htmlspecialchars($data['course']) ?>" required>
            </label>
            <label>
                Year Level
                <select name="year_level" required>
                    <?php for ($year = 1; $year <= 5; $year++): ?>
                        <option value="<?= $year ?>" <?= $data['year_level'] == $year ? 'selected' : '' ?>>Year <?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </label>
            <div class="form-actions">
                <a class="btn btn-secondary" href="index.php">Cancel</a>
                <button class="btn btn-primary" type="submit">Update Student</button>
            </div>
        </form>
    </section>
</div>
</body>
</html>
