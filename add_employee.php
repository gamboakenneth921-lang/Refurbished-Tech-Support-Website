
<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name = $_POST['name'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    mysqli_query($conn,
                "INSERT INTO employees(name,role,status)
                VALUES('$name','$role','$status')");

header("Location: dashboard.php");
}

?>