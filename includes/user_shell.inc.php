<?php

function skillgap_user_skills($learner) {
    if (function_exists('skillgap_split_skills')) {
        return skillgap_split_skills($learner['current_skills'] ?? '');
    }

    $parts = preg_split('/[,;\n]+/', strtolower($learner['current_skills'] ?? ''));
    return array_values(array_filter(array_map('trim', $parts)));
}

function skillgap_user_shell_start($active, $pageTitle, $assetPrefix, $learner) {
    $name = trim(($learner['Fname'] ?? '') . ' ' . ($learner['Lname'] ?? ''));
    $firstName = $learner['Fname'] ?? 'Learner';
    $email = $learner['email'] ?? '';
    $targetRole = $learner['target_role'] ?? 'Not selected';
    $background = $learner['Dept'] ?? 'Not added';
    $experience = $learner['experience_level'] ?? ($learner['Year_of_study'] ?? 'Entry');
    $skills = skillgap_user_skills($learner);

    $nav = [
        'dashboard' => ['Dashboard', $assetPrefix . 'home.php', 'grid'],
        'recommendations' => ['Recommendations', $assetPrefix . 'services.php', 'briefcase'],
        'profile' => ['Profile', $assetPrefix . 'profile.php', 'user'],
        'logout' => ['Logout', $assetPrefix . 'includes/logout.inc.php', 'logout'],
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="<?php echo $assetPrefix; ?>web/css/skillgap.css" rel="stylesheet" type="text/css">
</head>
<body class="dashboard-body">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <div class="sidebar-logo">
                <!-- <img src="<?php echo $assetPrefix; ?>dist/assets/images/logo/nile1_logo.png" alt="SkillBridge"> -->
                <span>SkillBridge</span>
            </div>

            <div class="sidebar-welcome">
                <strong>Welcome back <?php echo htmlspecialchars($firstName); ?></strong>
                <span><?php echo htmlspecialchars($targetRole); ?></span>
            </div>

            <p class="sidebar-title">Learner</p>
            <nav class="sidebar-nav">
                <?php foreach ($nav as $key => $item) { ?>
                    <a href="<?php echo htmlspecialchars($item[1]); ?>" class="<?php echo $active === $key ? 'active' : ''; ?>">
                        <span class="side-icon <?php echo htmlspecialchars($item[2]); ?>"></span>
                        <?php echo htmlspecialchars($item[0]); ?>
                    </a>
                <?php } ?>
            </nav>
        </aside>

        <main class="dashboard-main-area">
            <section class="dashboard-content">
<?php
}

function skillgap_user_shell_end($assetPrefix, $learner) {
    $name = trim(($learner['Fname'] ?? '') . ' ' . ($learner['Lname'] ?? ''));
    $email = $learner['email'] ?? '';
    $targetRole = $learner['target_role'] ?? 'Not selected';
    $background = $learner['Dept'] ?? 'Not added';
    $experience = $learner['experience_level'] ?? ($learner['Year_of_study'] ?? 'Entry');
    $skills = skillgap_user_skills($learner);
    $initial = strtoupper(substr($learner['Fname'] ?? 'L', 0, 1));
?>
            </section>

            <aside class="dashboard-rail">
                <article class="rail-card profile-summary">
                    <div class="avatar-circle"><?php echo htmlspecialchars($initial); ?></div>
                    <h3><?php echo htmlspecialchars($name ?: 'Learner'); ?></h3>
                    <p><?php echo htmlspecialchars($email); ?></p>
                </article>

                <article class="rail-card">
                    <h3>Learner Info</h3>
                    <div class="info-list">
                        <div>
                            <strong>Target Role</strong>
                            <span><?php echo htmlspecialchars($targetRole); ?></span>
                        </div>
                        <div>
                            <strong>Background</strong>
                            <span><?php echo htmlspecialchars($background); ?></span>
                        </div>
                        <div>
                            <strong>Experience</strong>
                            <span><?php echo htmlspecialchars($experience); ?></span>
                        </div>
                        <div>
                            <strong>Username</strong>
                            <span><?php echo htmlspecialchars($email); ?></span>
                        </div>
                    </div>
                </article>

                <article class="rail-card">
                    <h3>Skills</h3>
                    <div class="tag-list compact">
                        <?php foreach (array_slice($skills, 0, 8) as $skill) { ?>
                            <span class="tag good"><?php echo htmlspecialchars($skill); ?></span>
                        <?php } ?>
                        <?php if (count($skills) === 0) { ?>
                            <span class="tag">No skills yet</span>
                        <?php } ?>
                    </div>
                </article>
            </aside>
        </main>
    </div>
</body>
</html>
<?php
}
