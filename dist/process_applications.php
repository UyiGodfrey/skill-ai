<?php
require 'config.inc.php';

// get pending applications older than 5 minutes
$query = "SELECT A.*, R.type 
          FROM application A
          JOIN room R ON A.Room_id = R.Room_id
          WHERE A.Application_status = 0
          AND A.created_at <= NOW() - INTERVAL 1 MINUTE";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){

    $cgpa = floatval($row['cgpa']);
    $room_type = strtolower($row['type']);
    $app_id = $row['Application_id'];
    $room_id = $row['Room_id'];
    $student_id = $row['Student_id'];

    $approve = false;

    // RULES
    if($cgpa >= 4 && $room_type == 'executive'){
        $approve = true;
    }
    elseif($cgpa >= 3 && $cgpa < 6 && $room_type == 'conducive'){
        $approve = true;
    }
    elseif($cgpa < 6 && $room_type == 'normal'){
        $approve = true;
    }

    if($approve){

        // approve = 1
        mysqli_query($conn, 
            "UPDATE application SET Application_status = 1 WHERE Application_id = '$app_id'"
        );

        // assign room
        mysqli_query($conn, 
            "UPDATE student SET Room_id = '$room_id' WHERE Student_id = '$student_id'"
        );

        // mark room occupied
        // mysqli_query($conn, 
        //     "UPDATE room SET Allocated = 1 WHERE Room_id = '$room_id'"
        // );

    } else {

        // decline = 2
        mysqli_query($conn, 
            "UPDATE application SET Application_status = 2 WHERE Application_id = '$app_id'"
        );
    }
}
?>