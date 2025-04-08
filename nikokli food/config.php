<?php
$host = "sqlXXX.infinityfree.com"; // use your real hostname
$user = "your_db_user";
$pass = "your_db_password";
$db = "nikoli";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
