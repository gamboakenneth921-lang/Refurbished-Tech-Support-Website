<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$id = $_POST['id'];
$status = $_POST['status'];

mysqli_query($conn, "UPDATE employees SET status='$status' WHERE id='$id' ");

header("Location: dashboard.php");
exit();
}
?>