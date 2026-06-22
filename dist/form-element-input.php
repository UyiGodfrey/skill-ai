<?php
 require 'sidebar.php';
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'config.inc.php'; // database connection


function sendMail($to, $name, $messageBody){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pauljeremiah259@gmail.com';
        $mail->Password = 'qlctmehhezlnsnjp '; // NOT your real password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('pauljeremiah259@gmail.com', 'Student System');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Student Registration Nile HMS';
        $mail->Body = $messageBody;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}

$edit = false;

if(isset($_GET['id'])){
    $edit = true;
    $id = $_GET['id'];

    $resultEdit = mysqli_query($conn, "SELECT * FROM student WHERE Student_id='$id'");
    $rowEdit = mysqli_fetch_assoc($resultEdit);
}

if(isset($_POST['submit'])){

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $id = mysqli_real_escape_string($conn, $_POST['regno']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $level = mysqli_real_escape_string($conn, $_POST['currentlevel']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $health = mysqli_real_escape_string($conn, $_POST['health']);

    if(isset($_POST['edit_id'])){
        // ✅ UPDATE
        $edit_id = $_POST['edit_id'];

        $query = "UPDATE student SET 
            Fname='$fname',
            Lname='$lname',
            email='$email',
            Dept='$dept',
            Year_of_study='$level',
            gender='$gender',
            health='$health'
            WHERE Student_id='$edit_id'";

        if(mysqli_query($conn, $query)){
            echo "<script>alert('Student Updated Successfully');</script>";
        }

    } else {
        // ✅ INSERT (your existing code)
        $password = '123456';
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        $file_name = $_FILES['file']['name'];
        $temp_name = $_FILES['file']['tmp_name'];

        if(!empty($file_name)){
            move_uploaded_file($temp_name, "uploads/".$file_name);
        }

        $query = "INSERT INTO student 
        (Student_id, Fname, Lname, email, Dept, Year_of_study, gender, health, Pwd, file)
        VALUES 
        ('$id','$fname','$lname','$email','$dept','$level','$gender','$health', '$hashedPwd', '$file_name')";

        mysqli_query($conn, $query);
        
         $message = "
        Hello $fname,<br><br>
        Your account has been created successfully.<br>
        Reg No: $id<br>
        Password: 123456<br><br>
        Please login and change your password.<br>
        url: https://excelcbt.com.ng/hostel
        ";
    
        sendMail($email, $fname, $message);
        echo "<script>alert('Student Added Successfully');</script>";
        }
    }
    $result = mysqli_query($conn, "SELECT * FROM student");
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
                            <h3>Student Onboarding</h3>
                            <p class="text-subtitle text-muted">Provide important parameter for onboarding.</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Input</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Info</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <?php if($edit): ?>
                                    <input type="hidden" name="edit_id" value="<?php echo $rowEdit['Student_id']; ?>">
                                <?php endif; ?>
                                
                                <div class="col-md-6">
                                    
                                    <div class="form-group">
                                        <label for="basicInput">First Name</label>
                                        <input type="text" name="fname" class="form-control"
                                            value="<?php echo $edit ? $rowEdit['Fname'] : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="helpInputTop">Last name</label>
                                        
                                        <input type="text" name="lname" class="form-control"
                                        value="<?php echo $edit ? $rowEdit['Lname'] : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="helperText">Email</label>
                                        <input type="email" name="email" class="form-control"
                                        value="<?php echo $edit ? $rowEdit['email'] : ''; ?>">
                                        <p><small class="text-muted">Active email for notification.</small>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                                <label for="formFile" class="form-label">Addition document(optional)</label>
                                                <input class="form-control" type="file" name="file" id="formFile">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disabledInput">Department</label>
                                        <input type="text" name="dept" class="form-control"
                                        value="<?php echo $edit ? $rowEdit['Dept'] : ''; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="disabledInput">Current Level</label>
                                        <input type="text" name="currentlevel" class="form-control"
                                        value="<?php echo $edit ? $rowEdit['Year_of_study'] : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="disabledInput">Gender</label>
                                        <select class="form-control" name="gender" id="">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="disabledInput">Health Challenge (if any)</label>
                                        <input type="text" name="health" class="form-control">
                                    </div>
                                     <div class="form-group">
                                        <label for="disabledInput">Regno </label>
                                        <input type="text" name="regno" class="form-control" required
                                        value="<?php echo $edit ? $rowEdit['Student_id'] : ''; ?>">
                                    </div>
                                    
                                </div>
                            </div>
                             <button class="form-control" type="submit" name="submit">
                                <?php echo $edit ? 'Update Student' : 'Save'; ?>
                            </button>
                             </form>
                        </div>
                        
                    </div>
                </section>

              
               
              
            </div>
            <h3>Lists of students</h3>
                <section class="section">
                    <div class="card">
                       
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Level</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                        $result = mysqli_query($conn, "SELECT * FROM student");
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                        
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['Student_id']; ?></td>
                            <td><?php echo $row['Fname'] . " " . $row['Lname']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['Dept']; ?></td>
                            <td><?php echo $row['Year_of_study']; ?></td>
                            <td><?php echo $row['gender']; ?></td>
                        
                            <td>
                                <?php if ($row['status'] == 1) { ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php } else { ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php } ?>
                            </td>
                        
                            <td>
                                <a href="?id=<?php echo $row['Student_id']; ?>" class="badge bg-primary">Edit</a>
                        
                                <a href="delete_student.php?id=<?php echo $row['Student_id']; ?>" 
                                   class="badge bg-danger"
                                   onclick="return confirm('Are you sure?');">
                                   Delete
                                </a>
                        
                                <?php if ($row['status'] == 0) { ?>
                                    <a href="approve_student.php?id=<?php echo $row['Student_id']; ?>" 
                                       class="badge bg-success">
                                       Approve
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                            }
                        
                        } else {
                            echo "<tr><td colspan='7'>No students found</td></tr>";
                        }
                        ?>
                                                            
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2026 &copy; Bukar</p>
                    </div>
                    <div class="float-end">
                        <p>Developed <span class="text-danger"></i></span> by <a
                                href="#">Bukar</a></p>
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