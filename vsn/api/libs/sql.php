<?php

$servername = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'vsn';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo 'No Connection';
    die("Connection Failed" . $conn->connect_error);
}
?>