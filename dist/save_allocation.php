<?php

require '../includes/config.inc.php';

if(isset($_POST['student_id'])){

    $student_id  = mysqli_real_escape_string($conn, $_POST['student_id']);

    $room_id     = mysqli_real_escape_string($conn, $_POST['room_id']);

    $roommate_id = mysqli_real_escape_string($conn, $_POST['roommate_id']);


    if(empty($roommate_id)){

        $roommate_id = NULL;

        $query = "
            UPDATE application
            SET
                Room_id = '$room_id',
                roommate_id = NULL
            WHERE Student_id = '$student_id'
        ";

    }else{

        $query = "
            UPDATE application
            SET
                Room_id = '$room_id',
                roommate_id = '$roommate_id'
            WHERE Student_id = '$student_id'
        ";
    }


    $result = mysqli_query($conn, $query);

    if($result){

        header("Location: allocated_room.php?success=1");
        exit();

    }else{

        echo mysqli_error($conn);
    }
}
?>