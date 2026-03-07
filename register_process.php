<?php
session_start();

require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit();
}

// hash password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// check if email already exists
$check = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");

if(mysqli_num_rows($check) > 0){
    echo "Email already registered.";
} else {
    mysqli_query($conn, 
        "INSERT INTO user (fullname, email, password)
         VALUES ('$fullname', '$email', '$hashedPassword')"
    );

    echo "Registration successful.";
}

}
?>