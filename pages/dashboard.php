<?php
require_once '../includes/config.php';
requireLogin();

$totalGrads = $pdo->query("SELECT COUNT(*) FROM graduates")->fetchColumn();
$employed = $pdo->query("SELECT COUNT(*) FROM employment WHERE is_employed = 1")->fetchColumn();
$employmentRate = ($totalGrads > 0) ? round(($employed / $totalGrads) * 100, 2) : 0;

$trendSql = "SELECT g.graduation_year, COUNT(g.id) AS total, SUM(e.is_employed) AS employed
             FROM graduates g LEFT JOIN employment e ON g.id = e.graduate_id
             GROUP BY g.graduation_year ORDER BY g.graduation_year ASC";
$trendData = $pdo->query($trendSql)->fetchAll();
$years = []; $rates = [];
foreach ($trendData as $row) {
    $years[] = $row['graduation_year'];
    $rates[] = ($row['total'] > 0) ? round(($row['employed']/$row['total'])*100,2) : 0;
}

$salaryData = $pdo->query("SELECT g.program, AVG(e.monthly_salary) as avg_salary
    FROM graduates g JOIN employment e ON g.id = e.graduate_id
    WHERE e.is_employed = 1 AND e.monthly_salary IS NOT NULL GROUP BY g.program")->fetchAll();

$industryData = $pdo->query("SELECT CASE 
    WHEN employer_name LIKE '%school%' OR employer_name LIKE '%university%' THEN 'Education'
    WHEN employer_name LIKE '%hospital%' OR employer_name LIKE '%medical%' THEN 'Healthcare'
    WHEN employer_name LIKE '%bank%' OR employer_name LIKE '%finance%' THEN 'Finance'
    WHEN employer_name LIKE '%tech%' OR employer_name LIKE '%software%' THEN 'Technology'
    ELSE 'Other' END as industry, COUNT(*) as count
    FROM employment WHERE is_employed = 1 AND employer_name IS NOT NULL GROUP BY industry")->fetchAll();

$announcements = $pdo->query("SELECT * FROM announcements WHERE (expires_at IS NULL OR expires_at >= CURDATE()) ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentGrads = $pdo->query("SELECT * FROM graduates ORDER BY id DESC LIMIT 5")->fetchAll();

include '../includes/header.php';
?>

<h1>Dashboard</h1>
<div class="row mt-4">
    <div class="col-md-4"><div class="card stat-card bg-primary text-white"><div class="card-body"><i class="fas fa-user-graduate stat-icon"></i><h2><?= $totalGrads ?></h2><p>Total Graduates</p></div></div></div>
    <div class="col-md-4"><div class="card stat-card bg-success text-white"><div class="card-body"><i class="fas fa-briefcase stat-icon"></i><h2><?= $employed ?></h2><p>Employed Graduates</p></div></div></div>
    <div class="col-md-4"><div class="card stat-card bg-info text-white"><div class="card-body"><i class="fas fa-chart-line stat-icon"></i><h2><?= $employmentRate ?>%</h2><p>Employment Rate</p></div></div></div>
</div>

<div class="row mt-4">
    <div class="col-md-6"><div class="card"><div class="card-header">Employment Trend</div><div class="card-body"><canvas id="trendChart" height="150"></canvas></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Avg Salary by Program</div><div class="card-body"><canvas id="salaryChart" height="150"></canvas></div></div></div>
</div>
<div class="row mt-4">
    <div class="col-md-6"><div class="card"><div class="card-header">Industry Distribution</div><div class="card-body"><canvas id="industryChart" height="150"></canvas></div></div></div>
    <div class="col-md-6"><div class="card"><div class="card-header">Announcements</div><div class="card-body">
        <?php if (count($announcements) == 0): ?><p class="text-muted">No announcements.</p>
        <?php else: foreach ($announcements as $a): ?>
            <h6><?= htmlspecialchars($a['title']) ?> <small class="text-muted">(<?= $a['created_at'] ?>)</small></h6>
            <p><?= nl2br(htmlspecialchars($a['content'])) ?></p><hr>
        <?php endforeach; endif; ?>
    </div></div></div>
</div>

<h3 class="mt-5">Recent Graduates</h3>
<div class="table-responsive"><table class="table table-hover">
    <thead> </th><th>Student ID</th><th>Photo</th><th>Name</th><th>Program</th><th>Year</th></tr>
    <tbody><?php foreach ($recentGrads as $g): ?>
        <tr>
            <td><?= $g['id'] ?></td>
            <td><?= htmlspecialchars($g['student_id']) ?></td>
            <td><?php if ($g['profile_image']): ?><img src="/scholasys/assets/uploads/<?= $g['profile_image'] ?>" width="40" class="rounded-circle"><?php else: ?><i class="fas fa-user-circle fa-2x"></i><?php endif; ?></td>
            <td><?= htmlspecialchars($g['first_name'].' '.$g['last_name']) ?></td>
            <td><?= htmlspecialchars($g['program']) ?></td>
            <td><?= $g['graduation_year'] ?></td>
        </tr>
    <?php endforeach; ?></tbody>
</table></div>

<script>
new Chart(document.getElementById('trendChart'), {type:'line',data:{labels:<?= json_encode($years) ?>,datasets:[{label:'Employment Rate (%)',data:<?= json_encode($rates) ?>,borderColor:'#1e3c72',fill:true}]}});
new Chart(document.getElementById('salaryChart'), {type:'bar',data:{labels:<?= json_encode(array_column($salaryData,'program')) ?>,datasets:[{label:'Avg Salary (PHP)',data:<?= json_encode(array_column($salaryData,'avg_salary')) ?>,backgroundColor:'#1e3c72'}]}});
new Chart(document.getElementById('industryChart'), {type:'pie',data:{labels:<?= json_encode(array_column($industryData,'industry')) ?>,datasets:[{data:<?= json_encode(array_column($industryData,'count')) ?>,backgroundColor:['#1e3c72','#2a5298','#4a6fa5','#6c8ebf','#8fadcc']}]}});
</script>

<?php include '../includes/footer.php'; ?>