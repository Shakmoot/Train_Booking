<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "train_booking";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['source']) && isset($_GET['destination'])) {
    $source = $_GET['source'];
    $destination = $_GET['destination'];

    $sql = "SELECT * FROM trains WHERE from_station=? AND to_station=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $source, $destination);
} else {
    $sql = "SELECT * FROM trains";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$trains = array();
while ($row = $result->fetch_assoc()) {
    $trains[] = $row;
}

echo json_encode($trains);
$conn->close();
?>
