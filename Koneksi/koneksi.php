<?php
$servername = "localhost";
$username   = "tugascc_user";
$password   = "PasswordKuat123!";
$dbname     = "tugascc";

// Create connection
$db = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
