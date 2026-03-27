<?php
require_once '../includes/config.php';
requireLogin();

if ($_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) {
    header('Location: users.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $newPassword = $_POST['password'];

    if (!empty($newPassword)) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role = ? WHERE id = ?");
        $stmt->execute([$hashed, $role, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);
    }
    $success = "User updated successfully.";
}

include '../includes/header.php';
?>

<h2>Edit User</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label>Username</label>
        <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
    </div>
    <div class="mb-3">
        <label>New Password (leave blank to keep current)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select">
            <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update User</button>
    <a href="users.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include '../includes/footer.php'; ?>