<?php
require 'config.inc.php';

if(isset($_GET['id'])){
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM student WHERE Student_id='$id'";

    if(mysqli_query($conn, $query)){
        header("Location: form-element-input.php?msg=deleted");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>