<?php

 require '../includes/config.inc.php';

if(isset($_POST['student_id']) && isset($_POST['roommate_id'])){

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $roommate_id = mysqli_real_escape_string($conn, $_POST['roommate_id']);

    // Update roommate
    $query = "
        UPDATE application
        SET roommate_id = '$roommate_id'
        WHERE Student_id = '$student_id'
    ";

    $result = mysqli_query($conn, $query);

    if($result){

        header("Location: allocated_room.php?success=1");
        exit();

    }else{

        echo mysqli_error($conn);
    }
}
?>