<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$complete_id = $_POST['complete_id'];


mysqli_query($conn, "UPDATE tickets SET status='Completed' WHERE id='$complete_id' ");

header("Location: dashboard.php");
exit();
}
?>