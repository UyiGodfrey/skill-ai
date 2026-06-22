<?php
require '../includes/config.inc.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-hostel_manager.php");
    exit();
}

$learnerCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM student"))['total'] ?? 0;
$roleCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM job_roles"))['total'] ?? 0;
$jobCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM job_opportunities"))['total'] ?? 0;
$predictionCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM model_predictions"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge AI - Admin</title>
    <link href="../web/css/skillgap.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="app-shell">
        <header class="topbar">
            <div class="brand">SkillBridge AI Admin</div>
            <nav class="nav">
                <a href="index.php" class="active">Overview</a>
                <a href="../services.php">Learner View</a>
                <a href="../includes/logout.inc.php">Logout</a>
            </nav>
        </header>

        <main class="page">
            <section class="dashboard-hero">
                <div>
                    <p class="dashboard-eyebrow">Admin Overview</p>
                    <h1>Recommendation Platform Overview</h1>
                    <p class="lead">Use the database tables to maintain skills, role requirements, jobs, and imported model predictions.</p>
                </div>
                <div class="hero-actions">
                    <a href="../services.php" class="btn ghost">Learner view</a>
                    <a href="../MODEL_IMPORT.md" class="btn">Model notes</a>
                </div>
            </section>

            <section class="grid cols-3">
                <article class="card stat-card">
                    <div class="stat-label">Learners</div>
                    <div class="metric"><?php echo (int) $learnerCount; ?></div>
                    <p class="stat-footnote">Profiles available for scoring.</p>
                </article>
                <article class="card stat-card accent-blue">
                    <div class="stat-label">Roles</div>
                    <div class="metric blue"><?php echo (int) $roleCount; ?></div>
                    <p class="stat-footnote">Career paths with skill requirements.</p>
                </article>
                <article class="card stat-card accent-amber">
                    <div class="stat-label">Jobs</div>
                    <div class="metric amber"><?php echo (int) $jobCount; ?></div>
                    <p class="stat-footnote">Opportunities mapped to roles.</p>
                </article>
            </section>

            <section class="admin-strip">
                <article class="card model-card">
                    <h2>Model Import Readiness</h2>
                    <p>When your Python model is ready, import predictions into <strong>model_predictions</strong> using learner IDs and role IDs. The UI currently uses rule-based scoring until those predictions are wired into ranking.</p>
                    <div class="tag-list">
                        <span class="tag">model_predictions</span>
                        <span class="tag">match_score</span>
                        <span class="tag">payload JSON</span>
                    </div>
                </article>
                <aside class="card">
                    <div class="stat-label">Imported Predictions</div>
                    <div class="metric"><?php echo (int) $predictionCount; ?></div>
                    <p class="stat-footnote">Rows currently stored from model output.</p>
                </aside>
            </section>
        </main>
    </div>
</body>
</html>
