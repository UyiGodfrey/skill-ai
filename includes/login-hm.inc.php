<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['login-submit'])) {
    header("Location: ../login-hostel_manager.php");
    exit();
}

require 'config.inc.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['pwd'] ?? '';

if ($username === '' || $password === '') {
    header("Location: ../login-hostel_manager.php?error=Enter username and password");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM platform_admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$admin = $result->fetch_assoc()) {
    header("Location: ../login-hostel_manager.php?error=Admin account not found");
    exit();
}

$validPassword = password_verify($password, $admin['password_hash'])
    || hash_equals($admin['password_hash'], hash('sha256', $password));

if (!$validPassword) {
    header("Location: ../login-hostel_manager.php?error=Invalid admin login");
    exit();
}

$_SESSION['admin_id'] = $admin['admin_id'];
$_SESSION['username'] = $admin['username'];
$_SESSION['fname'] = $admin['full_name'];
$_SESSION['isadmin'] = 1;

header("Location: ../dist/index.php?login=success");
exit();
