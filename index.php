<?php
require 'includes/config.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill-gap app - Learner Login</title>
    <link href="web/css/skillgap.css" rel="stylesheet" type="text/css">
</head>
<body class="auth-login">
    <main class="auth-page" style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f5f3;">
        <div class="login-card" style="width:100%; max-width:400px; background:#fff; border:1px solid #e5e5e2; border-radius:12px; padding:2rem; margin:1rem;">

            <h1 style="font-size:1.1rem; font-weight:500; margin:0 0 0.25rem;">Welcome back</h1>
            <p style="font-size:0.875rem; color:#6b6b68; margin:0 0 1.5rem;">Sign in to your skill profile</p>

            <?php if (isset($_GET['error'])): ?>
                <div class="message error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="message"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>

            <form action="includes/login.inc.php" method="POST">
                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Email address</label>
                    <input type="email" name="email" placeholder="you@example.com" required style="width:100%; box-sizing:border-box;">
                </div>
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-size:0.8125rem; color:#6b6b68; margin-bottom:4px;">Password</label>
                    <input type="password" name="pwd" placeholder="Your password" required style="width:100%; box-sizing:border-box;">
                </div>
                <button type="submit" name="login-submit" class="btn" style="width:100%;">Login</button>
            </form>

            <div style="border-top:1px solid #e5e5e2; margin-top:1.25rem; padding-top:1rem;">
                <p class="muted-link">New learner? <a href="signup.php">Create your skill profile</a></p>
                <p class="muted-link">Platform team? <a href="login-manager.php">Admin login</a></p>
            </div>
        </div>
    </main>
</body>
</html>
