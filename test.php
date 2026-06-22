<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "excelcb2_hostel", "EDWOLWN7}QM^ld@&", "excelcb2_hostel");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

echo "Connected successfully!";
?>