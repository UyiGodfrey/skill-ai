<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'sidebar.php';
?>

<div id="main">

    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">

        <div class="page-title">
            <div class="row">

                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Hostel Room Allocation</h3>
                </div>

                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="index.html">Dashboard</a>
                            </li>

                            <li class="breadcrumb-item active">
                                Hostel Allocation
                            </li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>

        <section class="section">

            <div class="card">

<?php

$query1 = "
    SELECT

        a.Hostel_id,
        a.Room_id,
        a.roommate_id,

        s.Student_id,
        s.Fname,
        s.Lname,
        s.Mob_no,

        h.Hostel_name,

        r.Room_No,

        p.status AS payment_status,

        rm.Student_id AS roommate_student_id,
        rm.Fname AS roommate_fname,
        rm.Lname AS roommate_lname

    FROM application a

    INNER JOIN student s
        ON a.Student_id = s.Student_id

    INNER JOIN hostel h
        ON a.Hostel_id = h.Hostel_id

    LEFT JOIN room r
        ON a.Room_id = r.Room_id

    LEFT JOIN payments p
        ON a.Student_id = p.Student_id

    LEFT JOIN student rm
        ON a.roommate_id = rm.Student_id

    ORDER BY h.Hostel_name, s.Fname
";

$result1 = mysqli_query($conn, $query1);

if(!$result1){
    die(mysqli_error($conn));
}

?>

                <div class="card-body">

                    <table class="table table-striped" id="table1">

                        <thead>

                            <tr>

                                <th>Name</th>

                                <th>Student ID</th>

                                <th>Contact</th>

                                <th>Hostel</th>

                                <th>Room</th>

                                <th>Roommate</th>

                                <th>Payment</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

<?php

if(mysqli_num_rows($result1) == 0){

?>

<tr>

    <td colspan="8" class="text-center">
        No applications found
    </td>

</tr>

<?php

}else{

while($row = mysqli_fetch_assoc($result1)){

    $student_id = $row['Student_id'];

    $hostel_id = $row['Hostel_id'];

    $student_name =
        $row['Fname'].' '.$row['Lname'];


    // Payment Status
    if($row['payment_status'] == 1){

        $payment_status =
            '<span class="badge bg-success">
                Paid
            </span>';

    }else{

        $payment_status =
            '<span class="badge bg-danger">
                Unpaid
            </span>';
    }


    // Room Display
    if(empty($row['Room_No'])){

        $room_display =
            '<span class="badge bg-warning">
                Not Assigned
            </span>';

    }else{

        $room_display =
            'Room '.$row['Room_No'];
    }


    // Roommate Display
    if(empty($row['roommate_student_id'])){

        $roommate =
            '<span class="badge bg-secondary">
                Not Assigned
            </span>';

    }else{

        $roommate =
            $row['roommate_fname'].' '.
            $row['roommate_lname'].
            ' ('.
            $row['roommate_student_id'].
            ')';
    }

?>

<tr>

    <td>
        <?php echo $student_name; ?>
    </td>

    <td>
        <?php echo $row['Student_id']; ?>
    </td>

    <td>
        <?php echo $row['Mob_no']; ?>
    </td>

    <td>
        <?php echo $row['Hostel_name']; ?>
    </td>

    <td>
        <?php echo $room_display; ?>
    </td>

    <td>
        <?php echo $roommate; ?>
    </td>

    <td>
        <?php echo $payment_status; ?>
    </td>

    <td>

        <button
            class="btn btn-primary btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#allocateModal<?php echo $student_id; ?>"
        >

            Allocate

        </button>

    </td>

</tr>


<?php

// Fetch Rooms Based On Hostel
$roomQuery = "
    SELECT *
    FROM room
    WHERE Hostel_id = '$hostel_id'
";

$roomResult = mysqli_query($conn, $roomQuery);


// Fetch Possible Roommates
$mateQuery = "
    SELECT

        s.Student_id,
        s.Fname,
        s.Lname

    FROM application a

    INNER JOIN student s
        ON a.Student_id = s.Student_id

    WHERE a.Hostel_id = '$hostel_id'
    AND s.Student_id != '$student_id'
";

$mateResult = mysqli_query($conn, $mateQuery);

?>


<!-- Allocation Modal -->

<div class="modal fade"
     id="allocateModal<?php echo $student_id; ?>"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST"
                  action="save_allocation.php">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Allocate Hostel Room
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input type="hidden"
                           name="student_id"
                           value="<?php echo $student_id; ?>">


                    <!-- Room Selection -->

                    <div class="mb-3">

                        <label class="form-label">
                            Select Room
                        </label>

                        <select name="room_id"
                                class="form-control"
                                required>

                            <option value="">
                                -- Select Room --
                            </option>

<?php

while($room = mysqli_fetch_assoc($roomResult)){

?>

<option
    value="<?php echo $room['Room_id']; ?>"

    <?php

    if($row['Room_id'] == $room['Room_id']){
        echo 'selected';
    }

    ?>
>

    Room <?php echo $room['Room_No']; ?>

</option>

<?php } ?>

                        </select>

                    </div>


                    <!-- Roommate Selection -->

                    <div class="mb-3">

                        <label class="form-label">
                            Select Roommate
                        </label>

                        <select name="roommate_id"
                                class="form-control">

                            <option value="">
                                -- Select Roommate --
                            </option>

<?php

while($mate = mysqli_fetch_assoc($mateResult)){

?>

<option
    value="<?php echo $mate['Student_id']; ?>"

    <?php

    if($row['roommate_id'] == $mate['Student_id']){
        echo 'selected';
    }

    ?>
>

    <?php

    echo $mate['Fname'].' '.
         $mate['Lname'].' ('.
         $mate['Student_id'].')';

    ?>

</option>

<?php } ?>

                        </select>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-success">

                        Save Allocation

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php

    }
}

?>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </div>


    <footer>

        <div class="footer clearfix mb-0 text-muted">

            <div class="float-start">
                <p>2026 &copy;</p>
            </div>

            <div class="float-end">

                <p>
                    Developed by
                    <a href="#">
                        BOLNAAN BUKAR
                    </a>
                </p>

            </div>

        </div>

    </footer>

</div>


<script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script src="assets/vendors/simple-datatables/simple-datatables.js"></script>

<script>

let table1 = document.querySelector('#table1');

if(table1){

    new simpleDatatables.DataTable(table1);
}

</script>

<script src="assets/js/main.js"></script>

</body>
</html>