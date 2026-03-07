<?php
$conn = mysqli_connect("localhost", "root", "", "website");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>