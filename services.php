<?php
require 'includes/config.inc.php';

if (!isset($_SESSION['roll'])) {
    header("Location: index.php");
    exit();
}

require 'includes/recommendation.inc.php';
require 'includes/user_shell.inc.php';

$studentId = $_SESSION['roll'];
$stmt = $conn->prepare("SELECT s.*, r.title AS target_role FROM student s LEFT JOIN job_roles r ON s.target_role_id = r.role_id WHERE s.Student_id = ?");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$learner = $stmt->get_result()->fetch_assoc();
$roles = skillgap_fetch_roles($conn);
$jobs = skillgap_fetch_jobs($conn);

skillgap_user_shell_start('recommendations', 'SkillBridge AI - Recommendations', '', $learner);
?>
<h1 class="dash-title">Recommendations</h1>

<section class="dashboard-section" style="margin-top:0">
    <h2 class="dashboard-section-title">Role Fit</h2>
    <div class="dash-role-grid">
        <?php foreach ($roles as $role) {
            $match = skillgap_match_role($learner['current_skills'] ?? '', $role['required_skills'] ?? '');
        ?>
            <article class="dash-role-card">
                <div class="role-card-header">
                    <h3><?php echo htmlspecialchars($role['title']); ?></h3>
                    <span class="score-pill"><?php echo (int) $match['score']; ?>%</span>
                </div>
                <p class="muted"><?php echo htmlspecialchars($role['category']); ?> · <?php echo htmlspecialchars($role['demand_level']); ?> demand</p>
                <div class="progress"><span style="width: <?php echo (int) $match['score']; ?>%"></span></div>
                <div class="tag-list">
                    <?php foreach (array_slice($match['missing'], 0, 5) as $skill) { ?>
                        <span class="tag gap"><?php echo htmlspecialchars($skill); ?></span>
                    <?php } ?>
                    <?php if (count($match['missing']) === 0) { ?>
                        <span class="tag good">Ready for this role</span>
                    <?php } ?>
                </div>
            </article>
        <?php } ?>
    </div>
</section>

<section class="dashboard-section">
    <h2 class="dashboard-section-title">Open Jobs</h2>
    <div class="dash-table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Job</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Skills</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jobs as $job) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?><br><small><?php echo htmlspecialchars($job['salary_range']); ?></small></td>
                        <td><?php echo htmlspecialchars($job['company']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo htmlspecialchars($job['required_skills']); ?></td>
                        <td><?php echo htmlspecialchars($job['employment_type']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
<?php
skillgap_user_shell_end('', $learner);
