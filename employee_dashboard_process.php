<?php
require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    $email = $_POST['email'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $category = $_POST['category'];
   // $status = $_POST['status'];

    mysqli_query($conn,
                "INSERT INTO tickets(email,title,description,priority,category)
                VALUES('$email','$title','$description','$priority','$category')");

header("Location: employee_dashboard.php");
exit();
}

?>