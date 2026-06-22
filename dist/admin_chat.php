<?php
ob_start();
require 'config.inc.php';
session_start();
require 'sidebar.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ADMIN ID
$admin_id = "admin";

// GET SELECTED STUDENT
$student_id = $_GET['student_id'] ?? '';

// SEND MESSAGE
if(isset($_POST['send']) && !empty($_POST['message']) && !empty($student_id)){
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    mysqli_query($conn, "
        INSERT INTO message (sender_id, receiver_id, message)
        VALUES ('$admin_id', '$student_id', '$msg')
    ");

    header("Location: admin_chat.php?student_id=".$student_id);
    exit;
}
?>

<style>
/* MAIN LAYOUT */
.chat-wrapper {
    height: 80vh;
}

/* LEFT PANEL */
.user-list {
    height: 100%;
    overflow-y: auto;
    border-right: 1px solid #ddd;
    background: #fff;
}

.user-item {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.user-item a {
    text-decoration: none;
    color: #333;
    display: block;
}

.user-item:hover {
    background: #f5f5f5;
}

/* RIGHT PANEL */
.chat-section {
    height: 100%;
    display: flex;
    flex-direction: column;
}

/* MESSAGES */
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

.chat-left { text-align: left; }
.chat-right { text-align: right; }

.chat-left .chat-message {
    background: #e4e6eb;
}

.chat-right .chat-message {
    background: #28a745;
    color: #fff;
}

/* INPUT */
.chat-footer {
    padding: 10px;
    border-top: 1px solid #ddd;
    background: #fff;
}
</style>

<div id="main" style="margin-left: 300px;">
    <div class="page-heading">
    <h3>Admin Chat Panel</h3>

    <div class="row" style="height:80vh;">

        <!-- ✅ LEFT SIDE (STUDENT LIST) -->
        <div class="col-md-3 user-list">

            <?php
            $users = mysqli_query($conn, "
                SELECT DISTINCT 
                    CASE 
                        WHEN sender_id = '$admin_id' THEN receiver_id
                        ELSE sender_id
                    END AS student_id
                FROM message
                WHERE sender_id = '$admin_id' OR receiver_id = '$admin_id'
            ");

            if(!$users){
                die("User Query Error: " . mysqli_error($conn));
            }

            if(mysqli_num_rows($users) == 0){
                echo "<p class='p-2 text-muted'>No students yet</p>";
            }

            while($u = mysqli_fetch_assoc($users)){
                $uid = $u['student_id'];

                echo "
                <div class='user-item'>
                    <a href='admin_chat.php?student_id=$uid'>
                        Student ID: $uid
                    </a>
                </div>";
            }
            ?>

        </div>

        <!-- ✅ RIGHT SIDE (CHAT AREA) -->
        <div class="col-md-9 chat-section">

            <?php if($student_id): ?>

                <!-- MESSAGES -->
                <div class="chat-box" id="chatBox">

                    <?php
                    $messages = mysqli_query($conn, "
                        SELECT * FROM message 
                        WHERE 
                            (sender_id = '$student_id' AND receiver_id = '$admin_id')
                            OR 
                            (sender_id = '$admin_id' AND receiver_id = '$student_id')
                        ORDER BY msg_id ASC
                    ");

                    if(!$messages){
                        die("Message Query Error: " . mysqli_error($conn));
                    }

                    if(mysqli_num_rows($messages) == 0){
                        echo "<p>No messages</p>";
                    }

                    while($row = mysqli_fetch_assoc($messages)){
                        $isAdmin = $row['sender_id'] == $admin_id;
                        $message = htmlspecialchars($row['message']);

                        if($isAdmin){
                            echo "
                            <div class='chat-right'>
                                <div class='chat-message'>{$message}</div>
                            </div>";
                        } else {
                            echo "
                            <div class='chat-left'>
                                <div class='chat-message'>{$message}</div>
                            </div>";
                        }
                    }
                    ?>

                </div>

                <!-- INPUT -->
                <div class="chat-footer">
                    <form method="POST" class="d-flex w-100">
                        <input type="text" name="message" class="form-control" style="margin-right:10px;" placeholder="Reply..." required>
                        <button type="submit" name="send" class="btn btn-success">Send</button>
                    </form>
                </div>

            <?php else: ?>

                <div class="p-3">
                    <p>Select a student to start chatting</p>
                </div>

            <?php endif; ?>

        </div>

    </div>
</div>
</div>

<!--<script>-->
// Auto scroll
<!--let chatBox = document.getElementById("chatBox");-->
<!--if(chatBox){-->
<!--    chatBox.scrollTop = chatBox.scrollHeight;-->
<!--}-->

// Auto refresh
<!--setInterval(function(){-->
<!--    location.reload();-->
<!--}, 5000);-->
<!--</script>-->