<?php
$server_name = "localhost";
$user_name = "root";
$password = "";
$db_name = "yogo_blast";

$conn = mysqli_connect($server_name, $user_name, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 ?>