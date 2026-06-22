<?php
require 'includes/config.inc.php';

if (!isset($_SESSION['roll'])) {
    header("Location: index.php");
    exit();
}

require 'includes/user_shell.inc.php';

$studentId = $_SESSION['roll'];

if (isset($_POST['save-profile'])) {
    $mobile = trim($_POST['mobile_no']);
    $background = trim($_POST['background']);
    $experience = trim($_POST['experience_level']);
    $targetRole = (int) $_POST['target_role_id'];
    $skills = trim($_POST['current_skills']);
    $goal = trim($_POST['career_goal']);
    $portfolio = trim($_POST['portfolio_url']);

    $stmt = $conn->prepare(
        "UPDATE student SET Mob_no = ?, Dept = ?, Year_of_study = ?, target_role_id = ?, current_skills = ?, experience_level = ?, career_goal = ?, portfolio_url = ? WHERE Student_id = ?"
    );
    $stmt->bind_param("sssisssss", $mobile, $background, $experience, $targetRole, $skills, $experience, $goal, $portfolio, $studentId);
    $stmt->execute();

    header("Location: profile.php?success=Profile updated");
    exit();
}

$stmt = $conn->prepare("SELECT s.*, r.title AS target_role FROM student s LEFT JOIN job_roles r ON s.target_role_id = r.role_id WHERE s.Student_id = ?");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$learner = $stmt->get_result()->fetch_assoc();
$roles = mysqli_query($conn, "SELECT role_id, title FROM job_roles ORDER BY title ASC");

skillgap_user_shell_start('profile', 'SkillBridge AI - Profile', '', $learner);
?>
<h1 class="dash-title">Profile</h1>

<?php if (isset($_GET['success'])) { ?>
    <div class="message"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php } ?>

<section class="dash-panel">
    <form action="profile.php" method="POST" class="form-grid">
        <div class="form-row">
            <div>
                <label>Phone</label>
                <input type="text" name="mobile_no" value="<?php echo htmlspecialchars($learner['Mob_no']); ?>">
            </div>
            <div>
                <label>Background</label>
                <input type="text" name="background" value="<?php echo htmlspecialchars($learner['Dept']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div>
                <label>Experience level</label>
                <select name="experience_level">
                    <?php foreach (['Entry', 'Junior', 'Mid', 'Senior'] as $level) { ?>
                        <option value="<?php echo $level; ?>" <?php echo $learner['experience_level'] === $level ? 'selected' : ''; ?>><?php echo $level; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label>Target role</label>
                <select name="target_role_id">
                    <?php while ($role = mysqli_fetch_assoc($roles)) { ?>
                        <option value="<?php echo (int) $role['role_id']; ?>" <?php echo (int) $learner['target_role_id'] === (int) $role['role_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($role['title']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div>
            <label>Current skills</label>
            <textarea name="current_skills"><?php echo htmlspecialchars($learner['current_skills']); ?></textarea>
        </div>

        <div>
            <label>Career goal</label>
            <textarea name="career_goal"><?php echo htmlspecialchars($learner['career_goal']); ?></textarea>
        </div>

        <div>
            <label>Portfolio URL</label>
            <input type="url" name="portfolio_url" value="<?php echo htmlspecialchars($learner['portfolio_url']); ?>" placeholder="https://github.com/yourname">
        </div>

        <button type="submit" name="save-profile" class="btn">Save profile</button>
    </form>
</section>
<?php
skillgap_user_shell_end('', $learner);
