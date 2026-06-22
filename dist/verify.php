<?php
session_start();
require 'config.inc.php';
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
        $mail->Password = 'qlctmehhezlnsnjp'; // NOT your real password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('pauljeremiah259@gmail.com', 'Student System');
        $mail->addAddress($to, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Hostel Allocation Nile HMS';
        $mail->Body = $messageBody;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}




if (!isset($_GET['reference'])) {
    die("No reference supplied");
}

$reference = $_GET['reference'];

// ✅ Verify with Paystack
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_test_26425bbd98cc0674fefb5d9c690cbf17d7026614"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

$result = json_decode($response, true);

if (!$result || !isset($result['data'])) {
    die("Invalid response from Paystack");
}

// ✅ Get payment record from DB
$query = mysqli_query($conn, "SELECT * FROM payments WHERE reference='$reference'");
$payment = mysqli_fetch_assoc($query);

if (!$payment) {
    die("Transaction not found");
}

// ✅ Prevent duplicate processing
if ($payment['status'] == 1) {
    die("Payment already processed");
}

if ($result['data']['status'] === "success") {

    $room_id = $payment['room_id'];
    $student_id = $payment['student_id'];

    // ✅ Update payment
    mysqli_query($conn, "
        UPDATE payments 
        SET status = 1, paid_at = NOW()
        WHERE reference = '$reference'
    ");

    // ✅ Update application
    mysqli_query($conn, "
        UPDATE application 
        SET Application_status = 1 
        WHERE Student_id = '$student_id'
        ORDER BY Application_id DESC LIMIT 1
    ");

    // ✅ Allocate room safely
    mysqli_query($conn, "
       UPDATE room 
        SET Allocated = 1
        WHERE Room_id = '$room_id'
        AND Allocated = 0
    ");
      if(mysqli_affected_rows($conn) == 0){
        die("Room already allocated");
    }

    mysqli_query($conn, "
            UPDATE student SET Room_id = $room_id 
            WHERE Student_id = '$student_id'
        ");

    // if (mysqli_affected_rows($conn) == 0) {
    //     die("Room already taken");
    // }
    
    
    $email = $result['data']['customer']['email'];
    $amount = $result['data']['amount'] / 100;
    $reference = $result['data']['reference'];
    $fname = $_SESSION['fname'];
    $message = "
        <h2>Payment Successful</h2>
        <p><strong>Reference:</strong> $reference</p>
        <p><strong>Amount:</strong> ₦$amount</p>
        <p>Your hostel room has been successfully allocated.</p>
    ";
    
        sendMail($email, $fname, $message);
    

    echo "<script>alert('Payment successful & Room allocated'); window.location='dashboard.php';</script>";

} else {
    echo "<script>alert('Payment failed'); window.location='ui-widgets-pricing.php';</script>";
}
?>