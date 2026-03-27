<hr>
<h3>Employment History</h3>
<a href="add_employment_history.php?grad_id=<?= $grad_id ?>" class="btn btn-sm btn-primary mb-2">Add Job</a>
<table class="table table-sm">
    <thead><tr><th>Employer</th><th>Job Title</th><th>Start Date</th><th>End Date</th><th>Actions</th></tr></thead>
    <tbody>
    <?php $hist = $pdo->prepare("SELECT * FROM employment_history WHERE graduate_id = ? ORDER BY start_date DESC");
    $hist->execute([$grad_id]);
    while ($job = $hist->fetch()): ?>
        <tr>
            <td><?= htmlspecialchars($job['employer']) ?></td>
            <td><?= htmlspecialchars($job['job_title']) ?></td>
            <td><?= $job['start_date'] ?></td>
            <td><?= $job['end_date'] ?? 'Present' ?></td>
            <td>
                <a href="edit_employment_history.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="delete_employment_history.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>