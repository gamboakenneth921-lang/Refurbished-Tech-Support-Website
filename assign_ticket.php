<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$id = $_POST['id'];


mysqli_query($conn, "UPDATE tickets SET status='Assigned' WHERE id='$id' ");

header("Location: dashboard.php");
exit();
}
?>