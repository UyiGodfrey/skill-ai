<?php
session_start();
require 'config.inc.php';
// session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// TEMPORARY TEST EMAIL (very important)


$amount = isset($_GET['amount']) ? (int)$_GET['amount'] * 100 : 0;
$room_id = isset($_GET['room_id']) ? $_GET['room_id'] : '';
if (!isset($_SESSION['email'])) {
    die("Please login again");
}
$email = $_SESSION['email'];
$student_id = $_SESSION['roll'];
// $_SESSION['room_id'] = $room_id;
// $room_id = $_GET['room_id'];

if ($amount <= 0) {
    die("Invalid amount");
}

$reference = "APP_" . time() . "_" . rand(1000,9999);
mysqli_query($conn, "
INSERT INTO payments (student_id, room_id, reference, amount, status)
VALUES ('$student_id', '$room_id', '$reference', '$amount', 0)
");

$_SESSION['room_id'] = $room_id;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Paystack Payment</title>
</head>
<body>

<h3>Processing payment... Please wait</h3>

<script src="https://js.paystack.co/v1/inline.js"></script>

<script>
function payWithPaystack() {
    let handler = PaystackPop.setup({
        key: 'pk_test_b026843ccd6f8291fa323eed3b6981cbd53306d1',
        email: "<?php echo $email; ?>",
        amount: <?php echo $amount; ?>,
        currency: "NGN",
        ref: "<?php echo $reference; ?>",

        callback: function(response) {
            // success → verify payment
            window.location.href = "verify.php?reference=" + response.reference;
        },

        onClose: function() {
            alert('Payment cancelled');
        }
    });

    handler.openIframe();
}

payWithPaystack();
</script>
</body>
</html>