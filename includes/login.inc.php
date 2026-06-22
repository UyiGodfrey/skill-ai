<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['login-submit'])) {
    header("Location: ../index.php");
    exit();
}

require 'config.inc.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['pwd'] ?? '';

if ($email === '' || $password === '') {
    header("Location: ../index.php?error=Enter your email and password");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM student WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    header("Location: ../index.php?error=No learner profile found for that email");
    exit();
}

if ((int) $row['status'] !== 1 || !password_verify($password, $row['Pwd'])) {
    header("Location: ../index.php?error=Invalid login details");
    exit();
}

$_SESSION['roll'] = $row['Student_id'];
$_SESSION['fname'] = $row['Fname'];
$_SESSION['lname'] = $row['Lname'];
$_SESSION['email'] = $row['email'];
$_SESSION['department'] = $row['Dept'];
$_SESSION['year_of_study'] = $row['Year_of_study'];
$_SESSION['target_role_id'] = $row['target_role_id'];

header("Location: ../dist/dashboard.php?login=success");
exit();
