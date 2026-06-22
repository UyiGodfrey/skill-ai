<?php
require 'includes/config.inc.php';

if (isset($_POST['signup-submit'])) {
    $learnerId = trim($_POST['learner_id']);
    $email = trim($_POST['email']);
    $fname = trim($_POST['first_name']);
    $lname = trim($_POST['last_name']);
    $mobile = trim($_POST['mobile_no']);
    $background = trim($_POST['background']);
    $experience = trim($_POST['experience_level']);
    $targetRole = (int) $_POST['target_role_id'];
    $skills = trim($_POST['current_skills']);
    $goal = trim($_POST['career_goal']);
    $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT Student_id FROM student WHERE Student_id = ? OR email = ?");
    $check->bind_param("ss", $learnerId, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        header("Location: signup.php?error=Learner ID or email already exists");
        exit();
    }

    $stmt = $conn->prepare(
        "INSERT INTO student
        (Student_id, email, Fname, Lname, Mob_no, Dept, Year_of_study, Pwd, status, target_role_id, current_skills, experience_level, career_goal)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssssssisss", $learnerId, $email, $fname, $lname, $mobile, $background, $experience, $pwd, $targetRole, $skills, $experience, $goal);

    if ($stmt->execute()) {
        header("Location: index.php?success=Profile created. Login to view your recommendations");
        exit();
    }

    header("Location: signup.php?error=Could not create profile");
    exit();
}

$roles = mysqli_query($conn, "SELECT role_id, title FROM job_roles ORDER BY title ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge AI - Create Profile</title>
    <link href="web/css/skillgap.css" rel="stylesheet" type="text/css">
    <style>
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 480px) { .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="auth-login">
    <main style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f5f3; padding: 2rem 1rem;">
        <div style="width:100%; max-width:520px; background:#fff; border:1px solid #e5e5e2; border-radius:12px; padding:2rem; margin: 1rem 0;">

            <h1 style="font-size:1.1rem; font-weight:500; margin:0 0 0.25rem;">Create your learner profile</h1>
            <p style="font-size:0.875rem; color:#6b6b68; margin:0 0 1.5rem;">Add your skills and target role to get started</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form action="signup.php" method="POST" class="form-grid">

                <div class="form-row" style="margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Learner ID</label>
                        <input type="text" name="learner_id" placeholder="LRN-001" required style="width:100%; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Email address</label>
                        <input type="email" name="email" placeholder="you@example.com" required style="width:100%; box-sizing:border-box;">
                    </div>
                </div>

                <div class="form-row" style="margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">First name</label>
                        <input type="text" name="first_name" required style="width:100%; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Last name</label>
                        <input type="text" name="last_name" required style="width:100%; box-sizing:border-box;">
                    </div>
                </div>

                <div class="form-row" style="margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Phone</label>
                        <input type="text" name="mobile_no" required style="width:100%; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Background</label>
                        <input type="text" name="background" placeholder="e.g. Computer Science" required style="width:100%; box-sizing:border-box;">
                    </div>
                </div>

                <div class="form-row" style="margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Experience level</label>
                        <select name="experience_level" required style="width:100%; box-sizing:border-box;">
                            <option value="Entry">Entry</option>
                            <option value="Junior">Junior</option>
                            <option value="Mid">Mid</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Target role</label>
                        <select name="target_role_id" required style="width:100%; box-sizing:border-box;">
                            <?php while ($role = mysqli_fetch_assoc($roles)): ?>
                                <option value="<?php echo (int) $role['role_id']; ?>"><?php echo htmlspecialchars($role['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Current skills</label>
                    <textarea name="current_skills" placeholder="Python, SQL, Excel, data visualization" required style="width:100%; box-sizing:border-box; resize:vertical;"></textarea>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Career goal</label>
                    <textarea name="career_goal" placeholder="I want to become a data analyst in a fintech team." style="width:100%; box-sizing:border-box; resize:vertical;"></textarea>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Password</label>
                    <input type="password" name="pwd" required style="width:100%; box-sizing:border-box;">
                </div>

                <button type="submit" name="signup-submit" class="btn" style="width:100%;">Create profile</button>
            </form>

            <div style="border-top:1px solid #e5e5e2; margin-top:1.25rem; padding-top:1rem;">
                <p class="muted-link">Already have an account? <a href="index.php">Login</a></p>
            </div>

        </div>
    </main>
</body>
</html>