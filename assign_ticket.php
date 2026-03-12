<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$assign_id = $_POST['assign_id'];
$employee = $_POST['employee'];


mysqli_query($conn, "UPDATE tickets SET status='Assigned', assigned_to='$employee' WHERE id='$assign_id' ");

header("Location: dashboard.php");
exit();
}
?>