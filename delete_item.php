<?php
require 'db.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){


$id = $_POST['id'];

mysqli_query($conn, "DELETE FROM inventory WHERE id='$id'");

header("Location: inventory.php");
exit();
}
?>