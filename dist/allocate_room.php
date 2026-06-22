<?php
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
                            <h3>Applications Received</h3>
                            
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Application</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <?php
                        $hostel_id = $_SESSION['hostel_id'];
                        $query1 = "SELECT * FROM Student where Hostel_id = '$hostel_id'";
                        $result1 = mysqli_query($conn,$query1);
                        //select the hostel name from hostel table
                        $query6 = "SELECT * FROM Hostel WHERE Hostel_id = '$hostel_id'";
                        $result6 = mysqli_query($conn,$query6);
                        $row6 = mysqli_fetch_assoc($result6);
                        $hostel_name = $row6['Hostel_name'];


                        ?>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Student ID</th>
                                        <th>Hostel</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(mysqli_num_rows($result1)==0){
                                        echo '<tr><td colspan="4">No Rows Returned</td></tr>';
                                    }
                                    else{
                                        while($row1 = mysqli_fetch_assoc($result1)){
                                            //get the room_no of the student from room_id in room table
                                            $room_id = $row1['Room_id']; 
                                            $query7 = "SELECT * FROM Room WHERE Room_id = '$room_id'";
                                            $result7 = mysqli_query($conn,$query7);
                                            $row7 = mysqli_fetch_assoc($result7);
                                            $room_no = $row7['Room_No'];
                                            //student name
                                            $student_name = $row1['Fname']." ".$row1['Lname'];
                                            
                                            echo "<tr><td>{$student_name}</td><td>{$row1['Student_id']}</td><td>{$row1['Mob_no']}</td><td>{$hostel_name}</td><td><span class='badge bg-success'>{$room_no}</span></td></tr>\n";
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
                        <p>2026 &copy; </p>
                    </div>
                    <div class="float-end">
                        <p>Developed <span class="text-danger"></span> by <a
                                href="#">BOLNAAN BUKAR</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script src="assets/js/main.js"></script>
</body>

</html>