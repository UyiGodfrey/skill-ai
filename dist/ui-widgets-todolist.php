<?php
 require 'sidebar_student.php';
  include 'process_applications.php';
?>
<?php
require 'config.inc.php';
// session_start();

$student_id = $_SESSION['roll'];

// get latest application
$query = mysqli_query($conn, "
    SELECT * FROM application 
    WHERE Student_id = '$student_id'
    ORDER BY Application_id DESC 
    LIMIT 1
");

$application = mysqli_fetch_assoc($query);
?>
<?php
$paymentQ = mysqli_query($conn, "
    SELECT * FROM payments 
    WHERE student_id = '$student_id' 
    AND status = 1 
    ORDER BY id DESC 
    LIMIT 1
");

$payment = mysqli_fetch_assoc($paymentQ);
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
                            <h3>Application Status</h3>
                            <p class="text-subtitle text-muted">For user to check status</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Track Progress</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Task App Widget Starts -->
                <section class="tasks">
                    <div class="row">
                       <div class="col-lg-12">
                        <div class="card widget-todo">
                            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                                <h4 class="card-title d-flex">
                                    <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>Application Progress
                                </h4>
                            </div>
                    
                            <div class="card-body px-0 py-1">
                                <table class="table table-borderless">
                    
                                    <?php 
                                    $amount = 0;
                                    if($application): 
                                    $room_id = $application['Room_id'];

                                    $roomQ = mysqli_query($conn, "SELECT price FROM room WHERE Room_id='$room_id'");
                                    
                                    if(mysqli_num_rows($roomQ) > 0){
                                        $room = mysqli_fetch_assoc($roomQ);
                                        $amount = $room['price'];
                                    }
                                    ?>
                    
                                    <!-- STATUS ROW -->
                                    <tr>
                                        <td class="col-3">Application Status</td>
                                        <td class="col-6">
                                            <div class="progress">
                                                <?php
                                                if($application['Application_status'] == 0){
                                                    $width = "50%";
                                                    $color = "warning";
                                                    $text = "Pending";
                                                } elseif($application['Application_status'] == 1){
                                                    $width = "100%";
                                                    $color = "success";
                                                    $text = "Approved";
                                                } else {
                                                    $width = "100%";
                                                    $color = "danger";
                                                    $text = "Declined";
                                                }
                                                ?>
                                                <div class="progress-bar bg-<?php echo $color; ?>" 
                                                     style="width: <?php echo $width; ?>">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="col-3 text-center"><?php echo $text; ?></td>
                                    </tr>
                    
                                    <!-- CGPA -->
                                    <tr>
                                        <td>CGPA</td>
                                        <td colspan="2"><?php echo $application['cgpa']; ?></td>
                                    </tr>
                    
                                    <!-- ROOM -->
                                    <tr>
                                        <td>Room ID</td>
                                        <td colspan="2"><?php echo $application['Room_id']; ?></td>
                                    </tr>
                    
                                    <!-- PAYMENT BUTTON -->
                                    <tr>
                                    <td colspan="3" class="text-center">
                                    
                                    <?php if($payment): ?>
                                        
                                        <a href="receipt.php?ref=<?php echo $payment['reference']; ?>" 
                                           class="btn btn-primary">
                                           Download Receipt
                                        </a>
                                    
                                    <?php elseif($application['Application_status'] == 1): ?>
                                        
                                        <a href="pay.php?room_id=<?php echo $application['Room_id']; ?>&amount=<?php echo $amount; ?>" 
                                        class="btn btn-success">
                                           Pay Now
                                        </a>
                                    
                                    <?php elseif($application['Application_status'] == 0): ?>
                                        
                                        <button class="btn btn-warning" disabled>Waiting for Approval</button>
                                    
                                    <?php else: ?>
                                        
                                        <button class="btn btn-danger" disabled>Application Declined</button>
                                    
                                    <?php endif; ?>
                                    
                                    </td>
                                    </tr>
                                                        
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                No application found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                    
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </section>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2026 &copy; Bukar Boolnaan</p>
                    </div>
                    <div class="float-end">
                        <!--<p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a-->
                        <!--        href="http://ahmadsaugi.com">A. Saugi</a></p>-->
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/dragula/dragula.min.js"></script>
    <script>
        dragula([document.getElementById("widget-todo-list")], { moves: function (e, a, t) { return t.classList.contains("cursor-move") } })
    </script>

    <script src="assets/js/main.js"></script>
</body>

</html>