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
$matches = skillgap_best_matches($conn, $learner, 3);
$topMatch = $matches[0] ?? null;
$skillCount = count(skillgap_split_skills($learner['current_skills'] ?? ''));

skillgap_user_shell_start('dashboard', 'SkillBridge AI - Dashboard', '', $learner);
?>
<h1 class="dash-title">Skill Gap Dashboard</h1>

<section class="dashboard-stats">
    <article class="dash-card">
        <h3>Target Role</h3>
        <strong><?php echo htmlspecialchars($learner['target_role'] ?? 'Set one'); ?></strong>
    </article>
    <article class="dash-card">
        <h3>Best Match</h3>
        <strong><?php echo $topMatch ? (int) $topMatch['match_score'] . '%' : '0%'; ?></strong>
    </article>
    <article class="dash-card">
        <h3>Skills Saved</h3>
        <strong><?php echo (int) $skillCount; ?></strong>
    </article>
    <article class="dash-card">
        <h3>Experience</h3>
        <strong><?php echo htmlspecialchars($learner['experience_level'] ?? 'Entry'); ?></strong>
    </article>
</section>

<section class="dash-panel">
    <h2>Recommendation Progress</h2>
    <p class="lead">Your profile is matched against career-role requirements. Improve your score by closing the missing skills shown below.</p>
    <?php if ($topMatch) { ?>
        <div class="progress"><span style="width: <?php echo (int) $topMatch['match_score']; ?>%"></span></div>
    <?php } ?>
</section>

<section class="dashboard-section">
    <h2 class="dashboard-section-title">Top Role Matches</h2>
    <div class="dash-role-grid">
        <?php foreach ($matches as $match) { ?>
            <article class="dash-role-card">
                <div class="role-card-header">
                    <h3><?php echo htmlspecialchars($match['title']); ?></h3>
                    <span class="score-pill"><?php echo (int) $match['match_score']; ?>%</span>
                </div>
                <div class="progress"><span style="width: <?php echo (int) $match['match_score']; ?>%"></span></div>
                <div class="tag-list">
                    <?php foreach (array_slice($match['missing_skills'], 0, 4) as $skill) { ?>
                        <span class="tag gap"><?php echo htmlspecialchars($skill); ?></span>
                    <?php } ?>
                    <?php if (count($match['missing_skills']) === 0) { ?>
                        <span class="tag good">Ready</span>
                    <?php } ?>
                </div>
            </article>
        <?php } ?>
    </div>
</section>
<?php
skillgap_user_shell_end('', $learner);
