<?php
session_start();

require 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){

$email = $_POST['email'];
$password = $_POST['password'];

$result = mysqli_query($conn, 
    "SELECT * FROM user WHERE email='$email'"
);

if(mysqli_num_rows($result) == 1){

    $user = mysqli_fetch_assoc($result);

    if(password_verify($password, $user['password'])){

        // store session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];

        header("Location: dashboard.php");
        exit();

    } else {
        echo "Wrong password.";
    }

} else {
    echo "User not found.";
}
exit();

}
?>