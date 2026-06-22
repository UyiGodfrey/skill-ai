<?php
  session_start();
  $servername =  "localhost"; //Docker service name or localhost
  $dBUsername = "excelcb2_jobrecomm";
  $dBPassword = "t1#%l;l[J[1AZ{1.";
  $dBName = "excelcb2_jobrecomm";
 // session_start();
  $conn=mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

  if (!$conn) {
    die("Connection Failed: ".mysqli_connect_error());
  }
?>
