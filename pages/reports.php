<?php
require_once '../includes/config.php';
requireLogin();

$programStats = $pdo->query("
    SELECT g.program, 
           COUNT(g.id) AS total_grads,
           SUM(e.is_employed) AS employed
    FROM graduates g
    LEFT JOIN employment e ON g.id = e.graduate_id
    GROUP BY g.program
")->fetchAll();

$topEmployers = $pdo->query("
    SELECT employer_name, COUNT(*) as hires
    FROM employment
    WHERE is_employed = 1 AND employer_name IS NOT NULL AND employer_name != ''
    GROUP BY employer_name
    ORDER BY hires DESC
    LIMIT 10
")->fetchAll();

include '../includes/header.php';
?>

<h1>Reports</h1>

<!-- PDF Download Button -->
<a href="pdf_report.php" target="_blank" class="btn btn-danger mb-3">
    <i class="fas fa-file-pdf"></i> Download PDF Report
</a>

<h3>Employment Rate by Program</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Program</th>
            <th>Total Graduates</th>
            <th>Employed</th>
            <th>Rate (%)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($programStats as $stat): ?>
        <tr>
            <td><?= htmlspecialchars($stat['program']) ?></td>
            <td><?= $stat['total_grads'] ?></td>
            <td><?= $stat['employed'] ?? 0 ?></td>
            <td><?= $stat['total_grads'] > 0 ? round(($stat['employed']/$stat['total_grads'])*100, 2) : 0 ?>%</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Top Employers</h3>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Employer</th>
            <th>Hires</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($topEmployers as $emp): ?>
        <tr>
            <td><?= htmlspecialchars($emp['employer_name']) ?></td>
            <td><?= $emp['hires'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>