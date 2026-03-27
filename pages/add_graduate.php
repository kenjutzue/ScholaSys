<?php
require_once '../includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $program = $_POST['program'];
    $year = $_POST['graduation_year'];

    $stmt = $pdo->prepare("INSERT INTO graduates (student_id, first_name, last_name, email, contact_number, program, graduation_year) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$student_id, $first_name, $last_name, $email, $contact, $program, $year]);

    header('Location: graduates.php');
    exit;
}

include '../includes/header.php';
?>

<h2>Add Graduate</h2>
<form method="post">
    <div class="mb-3"><label>Student ID</label><input type="text" name="student_id" class="form-control" required></div>
    <div class="mb-3"><label>First Name</label><input type="text" name="first_name" class="form-control" required></div>
    <div class="mb-3"><label>Last Name</label><input type="text" name="last_name" class="form-control" required></div>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
    <div class="mb-3"><label>Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
    <div class="mb-3"><label>Program</label><input type="text" name="program" class="form-control" required></div>
    <div class="mb-3"><label>Graduation Year</label><input type="number" name="graduation_year" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="graduates.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>