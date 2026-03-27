<?php
require_once '../includes/config.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM graduates WHERE id = ?");
$stmt->execute([$id]);
$grad = $stmt->fetch();
if (!$grad) { header('Location: graduates.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $program = $_POST['program'];
    $year = $_POST['graduation_year'];

    $stmt = $pdo->prepare("UPDATE graduates SET student_id=?, first_name=?, last_name=?, email=?, contact_number=?, program=?, graduation_year=? WHERE id=?");
    $stmt->execute([$student_id, $first_name, $last_name, $email, $contact, $program, $year, $id]);
    header('Location: graduates.php');
    exit;
}

include '../includes/header.php';
?>

<h2>Edit Graduate</h2>
<form method="post">
    <div class="mb-3"><label>Student ID</label><input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($grad['student_id']) ?>" required></div>
    <div class="mb-3"><label>First Name</label><input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($grad['first_name']) ?>" required></div>
    <div class="mb-3"><label>Last Name</label><input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($grad['last_name']) ?>" required></div>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($grad['email']) ?>" required></div>
    <div class="mb-3"><label>Contact Number</label><input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($grad['contact_number']) ?>"></div>
    <div class="mb-3"><label>Program</label><input type="text" name="program" class="form-control" value="<?= htmlspecialchars($grad['program']) ?>" required></div>
    <div class="mb-3"><label>Graduation Year</label><input type="number" name="graduation_year" class="form-control" value="<?= $grad['graduation_year'] ?>" required></div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="graduates.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>