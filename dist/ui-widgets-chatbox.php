<?php
ob_start();
require 'config.inc.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// session_start();
require 'sidebar_student.php';

// SEND MESSAGE
if(isset($_POST['send']) && !empty($_POST['message'])){
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $student_id = $_SESSION['roll'];
    $admin_id = "admin";
    $hostel_id = 1;

    mysqli_query($conn, "
        INSERT INTO message (sender_id, receiver_id, hostel_id, message)
        VALUES ('$student_id', '$admin_id', '$hostel_id', '$msg')
    ");

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<style>
.chat-container {
    display: flex;
    flex-direction: column;
    height: 75vh; /* responsive height */
}

.chat-box {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f5f7fb;
}

.chat-message {
    padding: 10px 15px;
    border-radius: 15px;
    margin-bottom: 10px;
    max-width: 70%;
    display: inline-block;
}

.chat-left {
    text-align: left;
}

.chat-right {
    text-align: right;
}

.chat-left .chat-message {
    background: #e4e6eb;
}

.chat-right .chat-message {
    background: #435ebe;
    color: #fff;
}

.chat-footer {
    padding: 10px;
    border-top: 1px solid #ddd;
    background: #fff;
    position: sticky;
    bottom: 0;
}
</style>

<div id="main">
    <div class="page-heading">
        <h3>Chat with Admin</h3>

        <div class="card">
            <div class="card-header">
                <h5>Support Chat</h5>
            </div>

            <div class="card-body p-0">
                <div class="chat-container">

                    <!-- CHAT AREA -->
                    <div class="chat-box" id="chatBox">

                      <?php
                        $student_id = $_SESSION['roll'] ?? '';
                        $admin_id = "admin";
                        
                        // stop if session missing
                        if(empty($student_id)){
                            echo "<p class='text-danger'>Session error. Please login again.</p>";
                        } else {
                        
                            $messages = mysqli_query($conn, "
                                SELECT * FROM message
                                WHERE 
                                    (sender_id = '$student_id' AND receiver_id = '$admin_id')
                                    OR 
                                    (sender_id = '$admin_id' AND receiver_id = '$student_id')
                                ORDER BY msg_id ASC
                            ");
                        
                            if(!$messages){
                                echo "<p class='text-danger'>Error loading messages</p>";
                            } else {
                        
                                if(mysqli_num_rows($messages) == 0){
                                    echo "<p class='text-center text-muted'>No messages yet</p>";
                                }
                        
                                while($row = mysqli_fetch_assoc($messages)){
                        
                                    $isStudent = $row['sender_id'] == $student_id;
                        
                                    // 🔥 STRONG SANITIZATION
                                    $message = htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8');
                        
                                    if($isStudent){
                                        echo '<div class="chat-right">
                                                <div class="chat-message">'.$message.'</div>
                                              </div>';
                                    } else {
                                        echo '<div class="chat-left">
                                                <div class="chat-message">'.$message.'</div>
                                              </div>';
                                    }
                                }
                            }
                        }
                        ?>
                    </div>

                    <!-- INPUT AREA -->
                    <div class="chat-footer">
                        <form method="POST" class="d-flex">
                            <input type="text" name="message" class="form-control me-2" placeholder="Type your message..." required>
                            <button type="submit" name="send" class="btn btn-primary">Send</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Auto scroll to bottom
window.onload = function(){
    let chatBox = document.getElementById("chatBox");
    if(chatBox){
        chatBox.scrollTop = chatBox.scrollHeight;
    }
};

// Auto refresh every 5 seconds
// setInterval(function(){
//     location.reload();
// }, 5000);
</script>

</body>
</html>