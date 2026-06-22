<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillBridge AI - Admin Login</title>
    <link href="web/css/skillgap.css" rel="stylesheet" type="text/css">
</head>
<body class="auth-login">
    <main style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f5f3; padding:2rem 1rem;">
        <div style="width:100%; max-width:400px; background:#fff; border:1px solid #e5e5e2; border-radius:12px; padding:2rem; margin:1rem 0;">

            <h1 style="font-size:1.1rem; font-weight:500; margin:0 0 0.25rem;">Admin login</h1>
            <p style="font-size:0.875rem; color:#6b6b68; margin:0 0 1.5rem;">SkillBridge AI platform team</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <form action="includes/login-hm.inc.php" method="POST">
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Username</label>
                    <input type="text" name="username" required style="width:100%; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Password</label>
                    <input type="password" name="pwd" required style="width:100%; box-sizing:border-box;">
                </div>
                <button type="submit" name="login-submit" class="btn" style="width:100%;">Login</button>
            </form>

            <div style="border-top:1px solid #e5e5e2; margin-top:1.25rem; padding-top:1rem;">
                <p class="muted-link">Learner access? <a href="index.php">Login as learner</a></p>
            </div>

        </div>
    </main>
</body>
</html>