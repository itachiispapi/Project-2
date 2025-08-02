<?php
$host = "localhost";
$username = "agoulding1";
$password = "agoulding1";
$database = "agoulding1"; 

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
