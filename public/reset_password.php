<?php
require_once '../includes/config.php';
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
if ($stmt->execute([$newHash])) {
    echo "Password reset successfully. Use admin/admin123 to login.";
} else {
    echo "Error resetting password.";
}