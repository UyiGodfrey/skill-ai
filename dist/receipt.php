<?php
require 'config.inc.php';

$reference = $_GET['ref'];

$query = mysqli_query($conn, "
SELECT p.*, r.Room_id, r.price, s.Email 
FROM payments p
JOIN room r ON p.room_id = r.Room_id
JOIN student s ON p.student_id = s.Student_id
WHERE p.reference = '$reference'
");

$data = mysqli_fetch_assoc($query);

if(!$data){
    die("Invalid receipt");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .receipt { max-width: 500px; margin: auto; border: 1px solid #ccc; padding: 20px; }
        h2 { text-align: center; }
        .row { margin: 10px 0; }
        .btn { margin-top: 20px; padding: 10px; background: green; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="receipt">
    <h2>Payment Receipt</h2>

    <div class="row"><strong>Reference:</strong> <?php echo $data['reference']; ?></div>
    <div class="row"><strong>Email:</strong> <?php echo $data['Email']; ?></div>
    <div class="row"><strong>Room ID:</strong> <?php echo $data['room_id']; ?></div>
    <div class="row"><strong>Amount:</strong> ₦<?php echo number_format($data['amount']/100); ?></div>
    <div class="row"><strong>Status:</strong> Paid</div>
    <div class="row"><strong>Date:</strong> <?php echo $data['paid_at']; ?></div>

    <button onclick="window.print()" class="btn">Download / Print</button>
</div>

</body>
</html>