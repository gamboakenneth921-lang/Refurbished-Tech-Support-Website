<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$id = $_POST['id'];
$status = $_POST['status'];
$total_qty = $_POST['total_qty'];
$available = $_POST['available'];


mysqli_query($conn, "UPDATE inventory SET status='$status' WHERE id='$id' ");
mysqli_query($conn, "UPDATE inventory SET total_qty='$total_qty' WHERE id='$id' ");
mysqli_query($conn, "UPDATE inventory SET available='$available' WHERE id='$id' ");


header("Location: inventory.php");
exit();
}

?>