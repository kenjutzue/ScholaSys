<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ScholaSys - Graduate Data Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/scholasys/assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/scholasys/pages/dashboard.php">
                <i class="fas fa-graduation-cap me-2"></i>ScholaSys
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($_SESSION['username'])): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="/scholasys/pages/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/scholasys/pages/graduates.php"><i class="fas fa-users"></i> Graduates</a></li>
                        <li class="nav-item"><a class="nav-link" href="/scholasys/pages/reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
                        <?php if (in_array($_SESSION['role'], ['admin','staff'])): ?>
                            <li class="nav-item"><a class="nav-link" href="/scholasys/pages/events.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
                            <li class="nav-item"><a class="nav-link" href="/scholasys/pages/announcements.php"><i class="fas fa-newspaper"></i> News</a></li>
                        <?php endif; ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="/scholasys/pages/users.php"><i class="fas fa-users-cog"></i> Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="/scholasys/pages/import_graduates.php"><i class="fas fa-file-import"></i> Import</a></li>
                            <li class="nav-item"><a class="nav-link" href="/scholasys/pages/custom_report.php"><i class="fas fa-chart-line"></i> Custom Report</a></li>
                            <li class="nav-item"><a class="nav-link" href="/scholasys/pages/newsletter.php"><i class="fas fa-envelope"></i> Newsletter</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="/scholasys/public/directory.php"><i class="fas fa-address-book"></i> Alumni Directory</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <span class="navbar-text me-3"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?> (<?= ucfirst($_SESSION['role']) ?>)</span>
                        <a href="/scholasys/pages/change_password.php" class="btn btn-outline-light btn-sm me-2"><i class="fas fa-key"></i> Change Password</a>
                        <a href="/scholasys/pages/twofa_setup.php" class="btn btn-outline-light btn-sm me-2"><i class="fas fa-shield-alt"></i> 2FA</a>
                        <a href="/scholasys/public/logout.php" class="btn btn-outline-light btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main class="container my-4">