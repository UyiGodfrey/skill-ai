<?php
include 'config.inc.php';

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "UPDATE student SET status = 1 WHERE Student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        header("Location: form-element-input.php?msg=approved");
        exit();
    } else {
        echo "Failed to approve student";
    }
}
?>