<?php
$servername = "localhost";
$username = "root";
$password = ""; // default for XAMPP
$dbname = "train_booking"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>