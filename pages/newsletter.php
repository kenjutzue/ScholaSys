<?php
require_once '../includes/config.php';
require_once '../vendor/autoload.php'; // must be before any use statements

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

requireLogin();

if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';
$recipients = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $group = $_POST['group'];

    if (empty($subject) || empty($message)) {
        $error = "Subject and message are required.";
    } else {
        // Build recipient query
        $sql = "SELECT email, first_name FROM graduates";
        $params = [];
        if ($group == 'program' && !empty($_POST['program'])) {
            $sql .= " WHERE program = ?";
            $params[] = $_POST['program'];
        } elseif ($group == 'year' && !empty($_POST['year'])) {
            $sql .= " WHERE graduation_year = ?";
            $params[] = $_POST['year'];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $recipients = $stmt->fetchAll();

        // SMTP settings (update these with your actual credentials)
        $smtpHost = 'smtp.gmail.com';
        $smtpPort = 587;
        $smtpUser = 'your_email@gmail.com';
        $smtpPass = 'your_app_password';
        $fromEmail = 'noreply@scholasys.com';
        $fromName = 'ScholaSys Alumni';

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUser;
        $mail->Password = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtpPort;
        $mail->setFrom($fromEmail, $fromName);
        $mail->isHTML(false);

        $sent = 0;
        foreach ($recipients as $r) {
            try {
                $mail->clearAddresses();
                $mail->addAddress($r['email'], $r['first_name']);
                $mail->Subject = $subject;
                $mail->Body = "Dear {$r['first_name']},\n\n$message\n\n-- ScholaSys Team";
                $mail->send();
                $sent++;
            } catch (Exception $e) {
                error_log("Failed to send to {$r['email']}: " . $e->getMessage());
            }
        }
        $success = "Email sent to $sent of " . count($recipients) . " recipients.";
    }
}

include '../includes/header.php';
?>

<h2>Send Newsletter</h2>
<?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
<form method="post">
    <div class="mb-3">
        <label>Recipient Group</label>
        <select name="group" id="groupSelect" class="form-select">
            <option value="all">All Alumni</option>
            <option value="program">By Program</option>
            <option value="year">By Graduation Year</option>
        </select>
    </div>
    <div class="mb-3" id="programDiv" style="display:none;">
        <label>Program</label>
        <select name="program" class="form-select">
            <?php
            $progs = $pdo->query("SELECT DISTINCT program FROM graduates ORDER BY program")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($progs as $p): ?>
                <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3" id="yearDiv" style="display:none;">
        <label>Graduation Year</label>
        <select name="year" class="form-select">
            <?php
            $years = $pdo->query("SELECT DISTINCT graduation_year FROM graduates ORDER BY graduation_year DESC")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($years as $y): ?>
                <option value="<?= $y ?>"><?= $y ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Subject</label>
        <input type="text" name="subject" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Message (plain text)</label>
        <textarea name="message" class="form-control" rows="10" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Emails</button>
    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form>

<script>
    const groupSelect = document.getElementById('groupSelect');
    const programDiv = document.getElementById('programDiv');
    const yearDiv = document.getElementById('yearDiv');
    groupSelect.addEventListener('change', function() {
        programDiv.style.display = this.value === 'program' ? 'block' : 'none';
        yearDiv.style.display = this.value === 'year' ? 'block' : 'none';
    });
</script>

<?php include '../includes/footer.php'; ?>