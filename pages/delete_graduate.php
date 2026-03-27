<?php
require_once '../includes/config.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM graduates WHERE id = ?");
$stmt->execute([$id]);
$grad = $stmt->fetch();
if (!$grad) header('Location: graduates.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];
    $program = $_POST['program'];
    $year = $_POST['graduation_year'];
    $show_dir = isset($_POST['show_in_directory']) ? 1 : 0;

    $profile_image = $grad['profile_image'];
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $id . '_' . time() . '.' . $ext;
        $target = '../assets/uploads/' . $filename;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            if ($profile_image && file_exists('../assets/uploads/' . $profile_image)) unlink('../assets/uploads/' . $profile_image);
            $profile_image = $filename;
        }
    }

    $cv_path = $grad['cv_path'];
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
        $filename = 'cv_' . $id . '_' . time() . '.' . $ext;
        $target = '../assets/uploads/' . $filename;
        if (move_uploaded_file($_FILES['cv_file']['tmp_name'], $target)) {
            if ($cv_path && file_exists('../assets/uploads/' . $cv_path)) unlink('../assets/uploads/' . $cv_path);
            $cv_path = $filename;
        }
    }

    $update = $pdo->prepare("UPDATE graduates SET student_id=?, first_name=?, last_name=?, email=?, contact_number=?, program=?, graduation_year=?, profile_image=?, cv_path=?, show_in_directory=? WHERE id=?");
    $update->execute([$student_id, $first_name, $last_name, $email, $contact, $program, $year, $profile_image, $cv_path, $show_dir, $id]);
    header('Location: graduates.php');
    exit;
}
include '../includes/header.php';
?>
<h2>Edit Graduate</h2>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3"><label>Student ID</label><input type="text" name="student_id" class="form-control" value="<?= htmlspecialchars($grad['student_id']) ?>" required></div>
    <div class="mb-3"><label>First Name</label><input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($grad['first_name']) ?>" required></div>
    <div class="mb-3"><label>Last Name</label><input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($grad['last_name']) ?>" required></div>
    <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($grad['email']) ?>" required></div>
    <div class="mb-3"><label>Contact</label><input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($grad['contact_number']) ?>"></div>
    <div class="mb-3"><label>Program</label><input type="text" name="program" class="form-control" value="<?= htmlspecialchars($grad['program']) ?>" required></div>
    <div class="mb-3"><label>Graduation Year</label><input type="number" name="graduation_year" class="form-control" value="<?= $grad['graduation_year'] ?>" required></div>
    <div class="mb-3"><label>Profile Picture</label><input type="file" name="profile_image" class="form-control" accept="image/*">
        <?php if ($grad['profile_image']): ?><img src="/scholasys/assets/uploads/<?= $grad['profile_image'] ?>" width="100" class="mt-2"><?php endif; ?>
    </div>
    <div class="mb-3"><label>CV (PDF)</label><input type="file" name="cv_file" class="form-control" accept=".pdf">
        <?php if ($grad['cv_path']): ?><a href="/scholasys/assets/uploads/<?= $grad['cv_path'] ?>" target="_blank">View CV</a><?php endif; ?>
    </div>
    <div class="form-check mb-3"><input type="checkbox" name="show_in_directory" class="form-check-input" value="1" <?= $grad['show_in_directory'] ? 'checked' : '' ?>>
        <label class="form-check-label">Show in public alumni directory</label>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="graduates.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include '../includes/footer.php'; ?>