<?php
require 'db.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){


$id = $_POST['id'];

mysqli_query($conn, "DELETE FROM employees WHERE id='$id'");

header("Location: dashboard.php");
exit();
}
?>